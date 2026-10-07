<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\BookingPaidNotification;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\FcmService;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI SIGNATURE
        |--------------------------------------------------------------------------
        */
        $serverKey = config('midtrans.server_key');

        $signatureKey = hash(
            'sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($signatureKey !== $request->signature_key) {
            Log::warning('Midtrans: Invalid signature', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | CARI BOOKING
        |--------------------------------------------------------------------------
        */
        $parts     = explode('-', $request->order_id);
        $bookingId = $parts[1] ?? null;
        $booking   = Booking::find($bookingId);

        if (!$booking) {
            Log::warning('Midtrans: Booking tidak ditemukan', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT SUCCESS → Generate QR Code
        |--------------------------------------------------------------------------
        */
        if (
            $request->transaction_status == 'settlement' ||
            ($request->transaction_status == 'capture' && $request->fraud_status == 'accept')
        ) {
            if ($booking->payment_status === 'paid') {
                return response()->json(['success' => true, 'message' => 'Already processed']);
            }

            // Generate QR Code
            $qrPath = $this->generateQrCode($booking);

            $booking->status         = 'confirmed';
            $booking->payment_status = 'paid';
            $booking->paid_at        = now();
            $booking->qr_code_path   = $qrPath;
            $booking->save();

            Log::info('Booking #' . $booking->id . ' PAID - QR: ' . $qrPath);

            // Kirim notifikasi database ke semua admin
            $admins = User::all();
            foreach ($admins as $admin) {
                $admin->notify(new BookingPaidNotification($booking));
            }

            // Kirim push notification FCM ke semua admin
            FcmService::sendToAdmins(
                'Pembayaran Berhasil! 🔔',
                'Booking #' . $booking->id . ' atas nama ' . $booking->name . ' telah dibayar.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT EXPIRED
        |--------------------------------------------------------------------------
        */
        if ($request->transaction_status == 'expire') {
            $booking->status         = 'expired';
            $booking->payment_status = 'failed';
            $booking->save();
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT FAILED / CANCELLED
        |--------------------------------------------------------------------------
        */
        if (
            $request->transaction_status == 'cancel' ||
            $request->transaction_status == 'deny'
        ) {
            $booking->status         = 'cancelled';
            $booking->payment_status = 'failed';
            $booking->save();
        }

        return response()->json(['success' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE QR CODE
    |--------------------------------------------------------------------------
    */
    private function generateQrCode(Booking $booking): string
    {
        if (empty($booking->unique_token)) {
            $booking->unique_token = \Illuminate\Support\Str::random(32);
            $booking->save();
            $booking->refresh();
        }

        $qrContent = url('/booking/verify/' . $booking->unique_token);
        $filename  = 'qrcodes/booking-' . $booking->id . '.svg';

        $svgContent = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($qrContent);

        Storage::disk('public')->put($filename, $svgContent);

        return $filename;
    }
}