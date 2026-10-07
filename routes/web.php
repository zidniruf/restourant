<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MidtransCallbackController;
use App\Models\Booking;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| FRONTEND (PUBLIC) — dipisah per halaman untuk SEO
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/menu', [FrontendController::class, 'menu'])->name('menu.index');
Route::get('/menu/{slug}', [FrontendController::class, 'menuShow'])->name('menu.show');
Route::get('/tentang-kami', [FrontendController::class, 'about'])->name('about');
Route::get('/galeri', [FrontendController::class, 'gallery'])->name('gallery');
Route::get('/promo', [FrontendController::class, 'promo'])->name('promo.index');
Route::get('/kontak-reservasi', [FrontendController::class, 'contact'])->name('contact');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'text/xml');
});

/*
|--------------------------------------------------------------------------
| BOOKING (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

Route::get('/booking/status', function () {
    return view('booking.status');
})->name('booking.status.form');

Route::post('/booking/status/search', [BookingController::class, 'searchStatus'])->name('booking.status.search');
Route::get('/booking/status/{id}', [BookingController::class, 'statusPage'])->name('booking.status');
Route::get('/booking/struk/{token}', [BookingController::class, 'struk'])->name('booking.struk');

Route::get('/booking/verify/{token}', [BookingController::class, 'verifyQr'])->name('booking.checkin.verify');
Route::get('/booking/approve/{id}', [BookingController::class, 'approve'])->name('booking.approve');
Route::post('/booking/reject/{id}', [BookingController::class, 'reject'])->name('booking.reject');

Route::get('/save-fcm-token', function (\Illuminate\Http\Request $request) {
    $user = Auth::user() ?? Auth::guard('web')->user() ?? User::first();

    if ($user) {
        $user->fcm_token = $request->token;
        $user->save();
        Log::info('FCM token saved for user: ' . $user->id);
    }

    return response()->json(['ok' => true]);
});

// ⚠️ Testing only — hapus atau kunci dengan middleware auth sebelum production
Route::get('/test-approve/{id}', [BookingController::class, 'approve']);
Route::get('/midtrans-test', function () {
    dd(config('midtrans.server_key'));
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/booking/{id}/kirim-wa', [BookingController::class, 'kirimWa'])
        ->name('admin.booking.kirimwa');

    Route::get('/admin/laporan/cetak', [BookingController::class, 'cetakLaporan'])
        ->name('admin.laporan.cetak');

    Route::get('/admin/booking/checkin/{token}', [BookingController::class, 'checkIn'])
        ->name('admin.booking.checkin');

    Route::get('/admin/scanner', function () {
        return view('admin.qr-scanner');
    })->name('admin.scanner');

    Route::get('/admin/notifications/poll', function () {
        try {
            $latestBooking = Booking::latest()->first();
            $latestPaid = Booking::where('payment_status', 'paid')->latest('paid_at')->first();

            return response()->json([
                'latest_booking_id'   => $latestBooking?->id,
                'latest_booking_name' => $latestBooking?->name,
                'latest_booking_date' => $latestBooking?->booking_date?->format('d M Y'),
                'latest_paid_id'      => $latestPaid?->id,
                'latest_paid_name'    => $latestPaid?->name,
                'timestamp'           => now()->timestamp,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    Route::get('/admin/bookings/calendar', function () {
        return Booking::all()->map(function ($booking) {
            $color = match ($booking->status) {
                'pending'   => '#f59e0b',
                'approved'  => '#22c55e',
                'paid'      => '#16a34a',
                'rejected'  => '#ef4444',
                'completed' => '#3b82f6',
                default     => '#6b7280',
            };

            return [
                'title' => $booking->name . ' (' . $booking->guest_count . ' org)',
                'start' => $booking->booking_date . 'T' . $booking->booking_time,
                'color' => $color,
                'url'   => '/admin/bookings/' . $booking->id . '/edit',
            ];
        });
    });
});

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);