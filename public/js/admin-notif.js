const AdminNotif = {
    lastBookingId: null,
    lastPaidId: null,
    initialized: false,

    playSound(type) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const sounds = {
                booking:      [[880,0,0.15],[660,0.2,0.15]],
                paid:         [[523,0,0.1],[659,0.12,0.1],[784,0.24,0.2]],
                checkin_ok:   [[784,0,0.15],[988,0.15,0.2]],
                checkin_fail: [[200,0,0.1,'sawtooth'],[150,0.1,0.2,'sawtooth']],
            };
            (sounds[type]||[]).forEach(([freq,delay,dur,wave='sine'])=>{
                const o=ctx.createOscillator(), g=ctx.createGain();
                o.connect(g); g.connect(ctx.destination);
                o.type=wave; o.frequency.value=freq;
                g.gain.setValueAtTime(0.3, ctx.currentTime+delay);
                g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime+delay+dur);
                o.start(ctx.currentTime+delay);
                o.stop(ctx.currentTime+delay+dur+0.05);
            });
        } catch(e){}
    },

    async requestPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            await Notification.requestPermission();
        }
    },

    showBrowserNotif(title, body, tag) {
        if (!('Notification' in window) || Notification.permission !== 'granted') return;
        try {
            new Notification(title, {
                body, icon: '/favicon.ico', tag,
                requireInteraction: tag === 'paid',
            });
        } catch(e){}
    },

    showToast(message, type) {
        const colors = {
            booking:      {bg:'#1a1500',border:'#f59e0b',text:'#fbbf24',icon:'📅'},
            paid:         {bg:'#001a0a',border:'#10b981',text:'#6ee7b7',icon:'💳'},
            checkin_ok:   {bg:'#001220',border:'#3b82f6',text:'#93c5fd',icon:'✅'},
            checkin_fail: {bg:'#1a0000',border:'#ef4444',text:'#fca5a5',icon:'❌'},
        };
        const c = colors[type] || colors.booking;
        const existing = document.querySelectorAll('.admin-toast');
        const top = 16 + (existing.length * 88);
        const el = document.createElement('div');
        el.className = 'admin-toast';
        el.style.cssText = `
            position:fixed;top:${top}px;right:16px;z-index:99999;
            background:${c.bg};border:1px solid ${c.border};border-left:4px solid ${c.border};
            border-radius:12px;padding:12px 14px;width:min(300px,calc(100vw-32px));
            box-shadow:0 8px 24px rgba(0,0,0,0.5);
            display:flex;gap:10px;align-items:flex-start;
            font-family:system-ui,sans-serif;
            animation:toastIn 0.3s ease;
        `;
        el.innerHTML = `
            <div style="font-size:20px;flex-shrink:0">${c.icon}</div>
            <div style="flex:1;font-size:13px;color:${c.text};line-height:1.5">${message}</div>
            <button onclick="this.closest('.admin-toast').remove()"
                style="color:#6b7280;font-size:18px;background:none;border:none;cursor:pointer;flex-shrink:0;padding:0;line-height:1">×</button>
        `;
        if (!document.getElementById('admin-toast-css')) {
            const s = document.createElement('style');
            s.id = 'admin-toast-css';
            s.textContent = `@keyframes toastIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}`;
            document.head.appendChild(s);
        }
        document.body.appendChild(el);
        setTimeout(()=>{ el.style.opacity='0'; el.style.transition='opacity .3s'; setTimeout(()=>el.remove(),300); }, 8000);
    },

    notify(type, title, body, message) {
        this.playSound(type);
        this.showBrowserNotif(title, body, type);
        this.showToast(message, type);
    },

    async poll() {
        try {
            const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
            const res  = await fetch('/admin/notifications/poll', {
                headers: {'Accept':'application/json','X-CSRF-TOKEN':csrf},
                signal: AbortSignal.timeout(8000),
            });
            if (!res.ok) return;
            const data = await res.json();

            if (!this.initialized) {
                // Simpan state awal tanpa notifikasi
                this.lastBookingId = data.latest_booking_id;
                this.lastPaidId    = data.latest_paid_id;
                this.initialized   = true;
                console.log('AdminNotif: Ready. BookingID:', this.lastBookingId, 'PaidID:', this.lastPaidId);
                return;
            }

            // Booking baru masuk
            if (data.latest_booking_id && data.latest_booking_id !== this.lastBookingId) {
                console.log('AdminNotif: NEW BOOKING detected!', data.latest_booking_id);
                this.notify(
                    'booking',
                    '📅 Booking Baru!',
                    `${data.latest_booking_name} — ${data.latest_booking_date}`,
                    `<strong>Booking Baru!</strong><br>${data.latest_booking_name} melakukan reservasi.`
                );
                this.lastBookingId = data.latest_booking_id;
            }

            // Pembayaran baru masuk
            if (data.latest_paid_id && data.latest_paid_id !== this.lastPaidId) {
                console.log('AdminNotif: NEW PAYMENT detected!', data.latest_paid_id);
                this.notify(
                    'paid',
                    '💳 Pembayaran Masuk!',
                    `${data.latest_paid_name} telah membayar DP.`,
                    `<strong>Pembayaran Masuk!</strong><br>${data.latest_paid_name} telah membayar DP reservasi.`
                );
                this.lastPaidId = data.latest_paid_id;
            }

        } catch(e) {
            if (e.name !== 'AbortError') console.warn('AdminNotif poll error:', e.message);
        }
    },

    init() {
        this.requestPermission();
        this.poll(); // Poll pertama untuk set baseline
        setInterval(() => this.poll(), 15000); // Poll tiap 15 detik
        console.log('AdminNotif: Polling every 15s');
    }
};

// Global untuk scanner
window.notifyCheckin = function(success, message) {
    AdminNotif.notify(
        success ? 'checkin_ok' : 'checkin_fail',
        success ? '✅ Check-in Berhasil' : '❌ Check-in Gagal',
        message, message
    );
};

document.addEventListener('DOMContentLoaded', () => AdminNotif.init());