// =====================================================
// ADMIN REALTIME NOTIFICATION
// Polling setiap 10 detik ke server
// =====================================================

const AdminNotif = {
    lastBookingId: null,
    lastPaidId: null,
    isInit: false,

    // Sound generator pakai Web Audio API (tidak perlu file audio)
    playSound(type) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();

            if (type === 'booking') {
                // Suara "ting-tong" untuk booking baru
                this.beep(ctx, 880, 0,    0.15, 'sine');
                this.beep(ctx, 660, 0.2,  0.15, 'sine');
            } else if (type === 'paid') {
                // Suara "sukses" untuk pembayaran
                this.beep(ctx, 523, 0,    0.1, 'sine');
                this.beep(ctx, 659, 0.12, 0.1, 'sine');
                this.beep(ctx, 784, 0.24, 0.2, 'sine');
            } else if (type === 'checkin_ok') {
                // Suara "ding" untuk checkin berhasil
                this.beep(ctx, 784, 0,   0.15, 'sine');
                this.beep(ctx, 988, 0.15, 0.2, 'sine');
            } else if (type === 'checkin_fail') {
                // Suara "bzzz" untuk checkin gagal
                this.beep(ctx, 200, 0,   0.1, 'sawtooth');
                this.beep(ctx, 150, 0.1, 0.2, 'sawtooth');
            }
        } catch(e) {
            console.log('Audio not supported');
        }
    },

    beep(ctx, freq, delay, duration, type) {
        const o = ctx.createOscillator();
        const g = ctx.createGain();
        o.connect(g);
        g.connect(ctx.destination);
        o.type = type;
        o.frequency.value = freq;
        g.gain.setValueAtTime(0.3, ctx.currentTime + delay);
        g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + delay + duration);
        o.start(ctx.currentTime + delay);
        o.stop(ctx.currentTime + delay + duration + 0.05);
    },

    // Minta izin browser notification
    async requestPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            await Notification.requestPermission();
        }
    },

    // Tampilkan browser notification
    showBrowserNotif(title, body, type) {
        if (!('Notification' in window) || Notification.permission !== 'granted') return;

        const icons = {
            booking: '📅',
            paid: '💳',
            checkin_ok: '✅',
            checkin_fail: '❌',
        };

        new Notification(title, {
            body: body,
            icon: '/favicon.ico',
            badge: '/favicon.ico',
            tag: type,
            requireInteraction: type === 'paid', // paid harus diklik
        });
    },

    // Tampilkan toast di dashboard
    showToast(message, type) {
        const colors = {
            booking:     { bg: '#1a1500', border: '#f59e0b', text: '#fbbf24', icon: '📅' },
            paid:        { bg: '#001a0a', border: '#10b981', text: '#6ee7b7', icon: '💳' },
            checkin_ok:  { bg: '#001220', border: '#3b82f6', text: '#93c5fd', icon: '✅' },
            checkin_fail:{ bg: '#1a0000', border: '#ef4444', text: '#fca5a5', icon: '❌' },
        };

        const c = colors[type] || colors.booking;
        const id = 'toast-' + Date.now();

        const el = document.createElement('div');
        el.id = id;
        el.style.cssText = `
            position:fixed; top:${16 + document.querySelectorAll('[id^="toast-admin"]').length * 80}px;
            right:16px; z-index:99999;
            background:${c.bg}; border:1px solid ${c.border}; border-left:4px solid ${c.border};
            border-radius:12px; padding:12px 16px; width:300px;
            box-shadow:0 8px 24px rgba(0,0,0,0.5);
            display:flex; gap:10px; align-items:flex-start;
            animation:toastSlide 0.3s ease; font-family:system-ui,sans-serif;
        `;
        el.setAttribute('id', 'toast-admin-' + id);
        el.innerHTML = `
            <div style="font-size:20px;flex-shrink:0">${c.icon}</div>
            <div style="flex:1;font-size:13px;color:${c.text};line-height:1.4">${message}</div>
            <button onclick="this.parentElement.remove()"
                style="color:#6b7280;font-size:16px;background:none;border:none;cursor:pointer;flex-shrink:0">×</button>
        `;

        if (!document.getElementById('toast-style')) {
            const s = document.createElement('style');
            s.id = 'toast-style';
            s.textContent = `@keyframes toastSlide {
                from { opacity:0; transform:translateX(20px); }
                to   { opacity:1; transform:translateX(0); }
            }`;
            document.head.appendChild(s);
        }

        document.body.appendChild(el);
        setTimeout(() => el.remove(), 8000);
    },

    // Polling ke server
    async poll() {
        try {
            const res  = await fetch('/admin/notifications/poll', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' }
            });
            const data = await res.json();

            // Booking baru
            if (data.latest_booking_id && data.latest_booking_id !== this.lastBookingId) {
                if (this.isInit && this.lastBookingId !== null) {
                    this.playSound('booking');
                    this.showBrowserNotif(
                        '📅 Booking Baru!',
                        data.latest_booking_name + ' — ' + data.latest_booking_date,
                        'booking'
                    );
                    this.showToast(
                        '<strong>Booking Baru!</strong><br>' + data.latest_booking_name + ' telah melakukan reservasi.',
                        'booking'
                    );
                }
                this.lastBookingId = data.latest_booking_id;
            }

            // Pembayaran baru
            if (data.latest_paid_id && data.latest_paid_id !== this.lastPaidId) {
                if (this.isInit && this.lastPaidId !== null) {
                    this.playSound('paid');
                    this.showBrowserNotif(
                        '💳 Pembayaran Masuk!',
                        data.latest_paid_name + ' telah membayar DP.',
                        'paid'
                    );
                    this.showToast(
                        '<strong>Pembayaran Masuk!</strong><br>' + data.latest_paid_name + ' telah membayar DP reservasi.',
                        'paid'
                    );
                }
                this.lastPaidId = data.latest_paid_id;
            }

            this.isInit = true;

        } catch(e) {
            console.log('Polling error:', e);
        }
    },

    init() {
        this.requestPermission();
        this.poll(); // langsung poll pertama kali
        setInterval(() => this.poll(), 10000); // polling tiap 10 detik
    }
};

// Fungsi global untuk scanner
window.notifyCheckin = function(success, message) {
    AdminNotif.playSound(success ? 'checkin_ok' : 'checkin_fail');
    AdminNotif.showBrowserNotif(
        success ? '✅ Check-in Berhasil' : '❌ Check-in Gagal',
        message,
        success ? 'checkin_ok' : 'checkin_fail'
    );
    AdminNotif.showToast(message, success ? 'checkin_ok' : 'checkin_fail');
};

// Start saat DOM ready
document.addEventListener('DOMContentLoaded', () => AdminNotif.init());