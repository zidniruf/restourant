<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$bookings = App\Models\Booking::latest()->take(3)->get(['id','name','status','total_price','payment_link','qr_code_path']);
foreach($bookings as $b) {
    echo $b->id . ' | ' . $b->name . ' | ' . $b->status . ' | Rp' . number_format($b->total_price) . ' | payment_link:' . ($b->payment_link ? 'ADA' : 'KOSONG') . ' | qr:' . ($b->qr_code_path ? 'ADA' : 'KOSONG') . "\n";
}