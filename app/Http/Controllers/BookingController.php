<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Models\RestaurantTable;
use App\Models\RestaurantSession;
use App\Services\MidtransService;
use Carbon\Carbon;


class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STORE BOOKING
    |--------------------------------------------------------------------------
    */

public function store(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDASI FORM
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'name'                  => 'required|string|max:255',
        'phone'                 => 'required|string|max:20',
        'booking_date'          => 'required|date|after_or_equal:tomorrow',
        'restaurant_session_id' => 'nullable|exists:restaurant_sessions,id',
        'restaurant_table_id'   => 'required|exists:restaurant_tables,id',
        'guest_count'           => 'required|integer|min:1',
        'duration_hours'        => 'nullable|integer|min:1|max:24',
        'menus'                 => 'nullable|array',
        'menus.*.id'            => 'required_with:menus|exists:items,id',
        'menus.*.qty'           => 'required_with:menus|integer|min:1',
    ], [
        'booking_date.after_or_equal'  => 'Reservasi minimal H-1.',
        'restaurant_table_id.required' => 'Silakan pilih Tempat.',
    ]);

    /*
    |--------------------------------------------------------------------------
    | CEK HARI LIBUR & JAM OPERASIONAL
    |--------------------------------------------------------------------------
    */
    $bookingDate = Carbon::parse($request->booking_date);

    $dayMap = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
    ];

    $dayNameBooking = $dayMap[$bookingDate->format('l')];
    $dayName        = $dayMap[now()->format('l')];

    $setting = Setting::first();

    $diluarJamKerja = false;
    $todayScheduleNow = null;

    if ($setting) {
        $businessHours  = collect($setting->business_hours ?? []);
        $todaySchedule  = $businessHours->firstWhere('day', $dayNameBooking);

        if ($todaySchedule && ($todaySchedule['is_closed'] ?? false)) {
            return back()
                ->withInput()
                ->withErrors([
                    'booking_date' =>
                        '🚫 Maaf, restoran tutup pada hari ' . $dayNameBooking .
                        ' (' . $bookingDate->format('d M Y') . '). ' .
                        'Silakan pilih tanggal lain.',
                ]);
        }

        $todayScheduleNow = $businessHours->firstWhere('day', $dayName);

        if ($todayScheduleNow && !($todayScheduleNow['is_closed'] ?? false)) {
            $jamSekarang = now()->format('H:i');
            $jamBuka     = $todayScheduleNow['open']  ?? '00:00';
            $jamTutup    = $todayScheduleNow['close'] ?? '23:59';

            if ($jamSekarang < $jamBuka || $jamSekarang > $jamTutup) {
                $diluarJamKerja = true;
            }
        }
    }

    // Konversi 08xx → 628xx
    $phone = preg_replace('/[^0-9]/', '', $request->phone ?? '');
    if (str_starts_with($phone, '0')) {
        $phone = '62' . substr($phone, 1);
    }

    /*
    |--------------------------------------------------------------------------
    | CEK KAPASITAS MEJA
    |--------------------------------------------------------------------------
    */

    $table = RestaurantTable::findOrFail($request->restaurant_table_id);

    if ($table->capacity && $request->guest_count > $table->capacity) {
        return back()
            ->withInput()
            ->withErrors([
                'guest_count' =>
                    'Jumlah tamu (' . $request->guest_count . ' orang) ' .
                    'melebihi kapasitas meja ' . $table->name .
                    ' (' . $table->capacity . ' orang).',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG BIAYA SEWA TEMPAT (jika berbayar)
    |--------------------------------------------------------------------------
    */

    $tableDurationHours = null;
    $tablePrice = 0;

    if ($table->is_paid) {
        if ($table->pricing_type === 'per_hour') {
            if (!$request->duration_hours) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'duration_hours' => 'Mohon pilih durasi jam untuk tempat ' . $table->name . '.',
                    ]);
            }
            $tableDurationHours = (int) $request->duration_hours;
            $tablePrice = $table->calculatePrice($tableDurationHours);
        } else {
            $tablePrice = $table->calculatePrice();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA SESI
    |--------------------------------------------------------------------------
    */

    $session = $request->restaurant_session_id
        ? RestaurantSession::find($request->restaurant_session_id)
        : null;

    /*
    |--------------------------------------------------------------------------
    | HITUNG TOTAL (menu + biaya sewa tempat)
    |--------------------------------------------------------------------------
    */

    $totalMenu = 0;
    $menus = $request->menus ?? [];

    foreach ($menus as $menu) {
        $item = Item::find($menu['id']);
        if (!$item) continue;
        $totalMenu += $item->price * $menu['qty'];
    }

    $total = $totalMenu + $tablePrice;

    /*
    |--------------------------------------------------------------------------
    | CREATE BOOKING
    |--------------------------------------------------------------------------
    */

    $booking = Booking::create([
        'name'                  => $request->name,
        'phone'                 => $phone,
        'booking_date'          => $request->booking_date,
        'booking_time'          => $session ? $session->start_time : null,
        'restaurant_session_id' => $request->restaurant_session_id,
        'restaurant_table_id'   => $request->restaurant_table_id,
        'guest_count'           => $request->guest_count,
        'event_time'            => $request->event_time ?: null,
        'food_ready_time'       => $request->food_ready_time ?: null,
        'note'                  => $request->note,
        'status'                => 'pending',
        'total_price'           => $total,
        'table_price'           => $tablePrice,
        'table_duration_hours'  => $tableDurationHours,
        'qr_token'              => Str::uuid(),
    ]);

    /*
    |--------------------------------------------------------------------------
    | CREATE BOOKING ITEMS
    |--------------------------------------------------------------------------
    */

    foreach ($menus as $menu) {
        $item = Item::find($menu['id']);
        if (!$item) continue;

        BookingItem::create([
            'booking_id' => $booking->id,
            'item_id'    => $item->id,
            'qty'        => $menu['qty'],
            'price'      => $item->price,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECT KE HALAMAN STATUS
    |--------------------------------------------------------------------------
    */
    if ($diluarJamKerja) {
        return redirect('/')
            ->with(
                'outside_hours',
                'Booking Anda berhasil dikirim! ...'
            );
    }

    return redirect('/')
        ->with('success', 'Booking berhasil dikirim! Silakan tunggu konfirmasi admin.');
}

    /*
    |--------------------------------------------------------------------------
    | APPROVE BOOKING
    |--------------------------------------------------------------------------
    */

    public function approve(string $id)
    {
        $booking  = Booking::findOrFail($id);
        $dpAmount = $booking->total_price * 0.5;

        try {
            $midtrans    = new MidtransService();
            $transaction = $midtrans->createTransaction($booking);

            // $transaction adalah array: ['token' => '...', 'redirect_url' => '...']
            $redirectUrl = is_array($transaction)
                ? ($transaction['redirect_url'] ?? null)
                : ($transaction->redirect_url ?? null);

            $booking->update([
                'status'       => 'approved',
            ]);

            return back()->with('success', 'Booking berhasil disetujui.');

        } catch (\Exception $e) {
            Log::error('APPROVE BOOKING ERROR: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyetujui booking: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT BOOKING
    |--------------------------------------------------------------------------
    */

    public function reject(Request $request, string $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:255',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'status'        => 'rejected',
            'reject_reason' => $request->reject_reason,
        ]);

        return back()->with('success', 'Booking berhasil ditolak.');
    }

public function kirimWa(string $id)
{
    $booking = Booking::with(['items.item', 'table', 'session'])->findOrFail($id);
    $setting = Setting::first();

    // Validasi nomor WA
    $waNumber = preg_replace('/[^0-9]/', '', $booking->phone ?? '');
    if (!$waNumber) {
        return back()->with('error', 'Nomor WhatsApp tidak ditemukan.');
    }

    // Pastikan unique_token tersedia
    if (empty($booking->unique_token)) {
        $booking->update(['unique_token' => \Illuminate\Support\Str::random(32)]);
    }

    // Data dasar
    $siteName  = $setting->site_name ?? 'Restaurant';
    $tgl       = \Carbon\Carbon::parse($booking->booking_date)
                    ->locale('id')
                    ->translatedFormat('l, d F Y');
    $tempat    = $booking->table->name ?? '-';
    $eventTime = $booking->event_time       ? substr($booking->event_time, 0, 5)       : '-';
    $foodTime  = $booking->food_ready_time  ? substr($booking->food_ready_time, 0, 5)  : '-';
    $total     = number_format($booking->total_price ?? 0, 0, ',', '.');
    $tamu      = $booking->guest_count ?? '-';
    $catatan   = $booking->note ?? '-';
    $strukUrl  = url('/booking/struk/' . $booking->unique_token);
    $garis     = "━━━━━━━━━━━━━━━━━━";

// Baris sesi (opsional)
$sesiLine = '';
if ($booking->session) {
    $start    = substr($booking->session->start_time, 0, 5);
    $end      = substr($booking->session->end_time, 0, 5);
    $sesiLine = " *Sesi*       : {$booking->session->name} ({$start}–{$end})\n";
}

// Baris biaya sewa tempat (opsional, hanya jika berbayar)
$tablePriceLine = '';
if (($booking->table_price ?? 0) > 0) {
    $durasiText = $booking->table_duration_hours
        ? " ({$booking->table_duration_hours} jam)"
        : ' (sekali masuk)';
    $tablePriceLine = " *Biaya Sewa Tempat* : Rp " .
        number_format($booking->table_price, 0, ',', '.') . $durasiText . "\n";
}

    // Daftar menu
    $menuList = $booking->items->isNotEmpty()
        ? $booking->items->map(fn($item) =>
            "  • {$item->item->title} x{$item->qty} = Rp " .
            number_format(($item->price ?? 0) * $item->qty, 0, ',', '.')
          )->implode("\n")
        : "  (Tidak memesan menu)";

    // Susun pesan
    $pesan = implode("\n", [
        "Halo *{$booking->name}* ",
        "",
        " *Reservasi Anda Telah Disetujui!*",
        "Berikut detail booking Anda di *{$siteName}*:",
        "",
        $garis,
        " *Nama*       : {$booking->name}",
        " *Tanggal*    : {$tgl}",
        " *Tempat*     : {$tempat}",
        $tablePriceLine . $sesiLine . " *Jam Acara*  : {$eventTime}",
        " *Makan Siap* : {$foodTime}",
        " *Tamu*       : {$tamu} orang",
        $garis,
        " *Pesanan Menu:*",
        $menuList,
        $garis,
        " *Total*      : Rp {$total}",
        " *Catatan*    : {$catatan}",
        $garis,
        "",
        " *Struk Reservasi:*",
        $strukUrl,
        "",
        "Terima kasih telah memilih *{$siteName}* ",
    ]);

    $url = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($pesan);

    return view('booking.wa-redirect', compact('url'));
}

    /*
    |--------------------------------------------------------------------------
    | STATUS PAGE
    |--------------------------------------------------------------------------
    */

    public function statusPage($id)
    {
        $booking = Booking::with('items.item')->findOrFail($id);

        return view('booking.status-result', compact('booking'));
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH STATUS
    |--------------------------------------------------------------------------
    */

public function searchStatus(Request $request)
{
    $identifier = $request->phone; // Input dari form

    $booking = Booking::where('name', $request->name)
        ->where(function($query) use ($identifier) {
            $query->where('phone', $identifier)
                  // Jika format Order ID Anda menggunakan awalan tertentu misal 'BOOK-', sesuaikan bagian ini:
                  ->orWhere('id', str_replace('BOOK-', '', $identifier))
                  ->orWhere('unique_token', $identifier);
        })
        ->latest()
        ->first();

    if (!$booking) {
        return back()->with('error', 'Booking tidak ditemukan. Periksa kembali nama dan No WhatsApp/Order ID Anda.');
    }

    return view('booking.status-result', compact('booking'));
}

public function struk(Request $request, string $token)
{
    $booking = Booking::with(['items.item', 'table', 'session'])
        ->where('unique_token', $token)
        ->firstOrFail();

    $transactionStatus = $request->query('transaction_status');
    $statusCode        = $request->query('status_code');

    // Update payment jika dari redirect Midtrans (webhook tidak jalan di lokal/ngrok)
    if (
        in_array($transactionStatus, ['settlement', 'capture']) &&
        $statusCode == '200' &&
        $booking->payment_status !== 'paid'
    ) {
        // Generate unique_token jika belum ada
        if (empty($booking->unique_token)) {
            $booking->unique_token = \Illuminate\Support\Str::random(32);
            $booking->save();
        }

        // Generate QR Code (sama persis dengan MidtransCallbackController)
        $qrContent  = url('/booking/verify/' . $booking->unique_token);
        $filename   = 'qrcodes/booking-' . $booking->id . '.svg';

        $svgContent = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($qrContent);

        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $svgContent);

        $booking->update([
            'payment_status' => 'paid',
            'status'         => 'confirmed',
            'paid_at'        => now(),
            'qr_code_path'   => $filename,
        ]);

        $booking->refresh();
    }

    $setting = Setting::first();

    return view('booking.struk', compact('booking', 'setting'));
}

    /*
    |--------------------------------------------------------------------------
    | VERIFY QR
    | Dipanggil saat admin scan QR Code pelanggan
    | QR berisi URL /booking/verify/{token}
    | → redirect ke halaman check-in admin
    |--------------------------------------------------------------------------
    */

public function verifyQr(string $token)
{
    $booking = Booking::with(['table', 'session'])
        ->where('qr_token', $token)
        ->orWhere('unique_token', $token)
        ->first();

    if (!$booking) {
        return response()->json([
            'success' => false,
            'message' => 'QR Code tidak valid atau booking tidak ditemukan.',
        ]);
    }

    // ✅ Cukup cek status approved/confirmed
    if (!in_array($booking->status, ['approved', 'confirmed'])) {
        return response()->json([
            'success' => false,
            'message' => 'Booking ' . $booking->name . ' belum disetujui admin.',
        ]);
    }

    return response()->json([
        'success' => true,
        'booking' => [
            'id'           => $booking->id,
            'name'         => $booking->name,
            'phone'        => $booking->phone,
            'booking_date' => $booking->booking_date->format('d M Y'),
            'guest_count'  => $booking->guest_count,
            'total_price'  => $booking->total_price,
            'status'       => $booking->status,
            'checkin'      => $booking->checkin_status,
            'table'        => $booking->table->name ?? '-',
            'session'      => $booking->session->name ?? '-',
        ],
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | CHECK-IN
    | Proses validasi kehadiran pelanggan
    | Dipanggil dari halaman scanner admin
    |--------------------------------------------------------------------------
    */

public function checkIn(string $token)
{
    if (!Auth::check()) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    }

    $booking = Booking::with(['table', 'session'])
        ->where('qr_token', $token)
        ->orWhere('unique_token', $token)
        ->first();

    if (!$booking) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Booking tidak ditemukan.',
        ]);
    }

    if ($booking->checkin_status === 'checked_in') {
        return response()->json([
            'status'  => 'already',
            'message' => 'Customer ' . $booking->name . ' sudah check-in sebelumnya.',
            'booking' => $this->formatBookingDetail($booking),
        ]);
    }

    // ✅ Cukup cek status approved/confirmed
    if (!in_array($booking->status, ['approved', 'confirmed'])) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Booking ' . $booking->name . ' belum disetujui admin.',
        ]);
    }

    $booking->update([
        'checkin_status' => 'checked_in',
        'checked_in_at'  => now(),
        'status'         => 'completed',
    ]);

    return response()->json([
        'status'  => 'success',
        'message' => 'Check-in berhasil! ' . $booking->name . ' telah tiba.',
        'name'    => $booking->name,
        'booking' => $this->formatBookingDetail($booking),
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | FORMAT DETAIL BOOKING
    | Helper untuk response JSON di checkIn
    |--------------------------------------------------------------------------
    */

private function formatBookingDetail(Booking $booking): array
{
    return [
        'id'             => $booking->id,
        'name'           => $booking->name,
        'phone'          => $booking->phone,
        'table'          => $booking->table->name ?? '-',
        'session'        => $booking->session->name ?? '-',
        'booking_date'   => $booking->booking_date->format('d M Y'),
        'guest_count'    => $booking->guest_count,
        'status'         => $booking->status,
        'checkin_status' => $booking->checkin_status,
        'checked_in_at'  => $booking->checked_in_at?->format('d M Y, H:i') . ' WIB',
    ];
}

/*
|--------------------------------------------------------------------------
| CETAK LAPORAN BULANAN
|--------------------------------------------------------------------------
*/
public function cetakLaporan(Request $request)
{
    $bulan = $request->get('bulan', now()->format('Y-m'));
    if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
        $bulan = now()->format('Y-m');
    }

    [$year, $month] = explode('-', $bulan);

    $bookings = Booking::with(['table', 'session', 'items.item'])
        ->whereYear('booking_date', $year)
        ->whereMonth('booking_date', $month)
        ->orderBy('booking_date')
        ->get();

    $totalBooking    = $bookings->count();
    $totalSelesai    = $bookings->where('status', 'completed')->count();
    $totalPendapatan = $bookings->sum('total_price');
    $bulanLabel      = \Carbon\Carbon::createFromDate($year, $month, 1)
                        ->locale('id')->translatedFormat('F Y');

    return view('admin.laporan', compact(
        'bookings', 'totalBooking', 'totalSelesai',
        'totalPendapatan', 'bulanLabel', 'bulan'
    ));
}
}