
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
        if (permission !== 'granted') return;

        messaging.getToken({
            vapidKey: 'BL8tuyZRciso21c0VfRY58icDlaYfwnilZp0FqiwwjZwHCSndF7U4Xv3K-t71kfR1EPtuPfHlkMhUB9X9tuxUQI'
        }).then(token => {
            if (!token) return;

            fetch('/save-fcm-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ token })
            });
        }).catch(err => console.error('FCM token error:', err));
    });

    // Notifikasi saat halaman admin terbuka (foreground)
    messaging.onMessage(payload => {
        const title = payload.notification?.title || 'Notifikasi';
        const body  = payload.notification?.body  || '';

        if (Notification.permission === 'granted') {
            new Notification(title, { body, icon: '/favicon.ico' });
        }
    });
})();
</script>