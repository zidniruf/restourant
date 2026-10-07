importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyA8QiVb40Zu4nPVVViHqo7EMbUq3Q3geuI",
    authDomain: "website-7d0a8.firebaseapp.com",
    projectId: "website-7d0a8",
    storageBucket: "website-7d0a8.firebasestorage.app",
    messagingSenderId: "1057811065355",
    appId: "1:1057811065355:web:29b523d8f3d6b0477e350e"
});

const messaging = firebase.messaging();

// Tampilkan notifikasi saat browser di background / ditutup
messaging.onBackgroundMessage(function(payload) {
    const title = payload.notification?.title || 'Notifikasi Baru';
    const body  = payload.notification?.body  || '';

    self.registration.showNotification(title, {
        body: body,
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        sound: '/sounds/notif.mp3',  // ← tambahkan ini
        data: { url: '/admin/bookings' }
    });
});

// Klik notifikasi → buka halaman admin
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data?.url || '/admin')
    );
});