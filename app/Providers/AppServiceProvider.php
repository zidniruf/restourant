<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS saat pakai ngrok atau production
        if (request()->isSecure() || str_contains(config('app.url'), 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Dynamic Favicon dari database
        FilamentView::registerRenderHook(
            'panels::head.end',
            function () {
                $setting = \App\Models\Setting::first();

                $iconUrl = $setting && $setting->logo
                    ? secure_asset('storage/' . $setting->logo)
                    : secure_asset('favicon.ico');

                return '
                    <link rel="icon" href="' . $iconUrl . '">
                    <link rel="shortcut icon" href="' . $iconUrl . '">
                ';
            }
        );

        // Admin notif + Firebase
        FilamentView::registerRenderHook(
            'panels::body.end',
            function () {
                $notifScript = '<script src="' . asset('js/admin-notif.js') . '?v=' . time() . '"></script>';

                $firebaseScript = <<<'HTML'
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js"></script>
<script>
(function() {
    firebase.initializeApp({
        apiKey: "AIzaSyA8QiVb40Zu4nPVVViHqo7EMbUq3Q3geuI",
        authDomain: "website-7d0a8.firebaseapp.com",
        projectId: "website-7d0a8",
        storageBucket: "website-7d0a8.firebasestorage.app",
        messagingSenderId: "1057811065355",
        appId: "1:1057811065355:web:29b523d8f3d6b0477e350e"
    });

    const messaging = firebase.messaging();

    Notification.requestPermission().then(permission => {
        if (permission !== "granted") return;
        messaging.getToken({
            vapidKey: "BL8tuyZRciso21c0VfRY58icDlaYfwnilZp0FqiwwjZwHCSndF7U4Xv3K-t71kfR1EPtuPfHlkMhUB9X9tuxUQI"
        }).then(token => {
            if (!token) return;
            fetch("/save-fcm-token", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]")?.content || ""
                },
                body: JSON.stringify({ token: token })
            }).then(() => console.log("FCM token saved"));
        }).catch(e => console.error("FCM error:", e));
    });

    messaging.onMessage(payload => {
        console.log("FCM foreground message:", payload);

        // Suara ding
        try {
            const ctx = new AudioContext();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 880;
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.5);
        } catch(e) {}

        // Tampilkan notifikasi via service worker
        navigator.serviceWorker.ready.then(registration => {
            registration.showNotification(
                payload.notification?.title || "Notifikasi",
                {
                    body: payload.notification?.body || "",
                    icon: "/favicon.ico",
                    data: { url: "/admin/bookings" }
                }
            );
        });
    });
})();
</script>
HTML;

                return $notifScript . $firebaseScript;
            }
        );
    }
}