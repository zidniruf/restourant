<x-filament::page>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css"/>

<style>
.fc { font-family: 'Segoe UI', system-ui, sans-serif !important; }
.fc-toolbar-title { font-size: 15px !important; font-weight: 700 !important; color: #0f172a !important; }
.fc-button {
    background: #f1f5f9 !important; border: 1px solid #e2e8f0 !important;
    color: #475569 !important; font-size: 12px !important;
    padding: 5px 10px !important; border-radius: 6px !important; box-shadow: none !important;
}
.fc-button:hover { background: #e2e8f0 !important; }
.fc-button-active { background: #f97316 !important; color: white !important; border-color: #f97316 !important; }
.fc-col-header-cell { background: #f8fafc !important; }
.fc-col-header-cell-cushion {
    font-size: 11px !important; font-weight: 600 !important; color: #94a3b8 !important;
    text-transform: uppercase !important; letter-spacing: 0.05em !important;
    padding: 8px 4px !important; text-decoration: none !important;
}
.fc-daygrid-day { cursor: pointer; transition: background 0.15s; }
.fc-daygrid-day:hover { background: #fff7ed !important; }
.fc-daygrid-day-number { font-size: 12px !important; color: #475569 !important; padding: 4px 6px !important; text-decoration: none !important; }
.fc-day-today { background: #fff7ed !important; }
.fc-day-today .fc-daygrid-day-number { color: #f97316 !important; font-weight: 700 !important; }
.fc-daygrid-day-events { display: none !important; }
.fc-scrollgrid { border: none !important; }
.fc-scrollgrid td, .fc-scrollgrid th { border-color: #f1f5f9 !important; }
.fc-toolbar { flex-wrap: wrap; gap: 8px; }

.booking-badge {
    position: absolute; top: 3px; right: 3px;
    width: 20px; height: 20px; border-radius: 50%;
    font-size: 10px; font-weight: 700; color: white;
    display: flex; align-items: center; justify-content: center;
    line-height: 1; z-index: 5;
}

.legend { display: flex; gap: 12px; flex-wrap: wrap; }
.legend-item { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #64748b; }
.legend-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

.detail-empty { text-align: center; padding: 32px 16px; color: #94a3b8; font-size: 13px; }
.detail-empty-icon { font-size: 32px; margin-bottom: 8px; }

.booking-card {
    border: 1px solid #e2e8f0; border-radius: 12px;
    overflow: hidden; margin-bottom: 12px;
    background: white; transition: box-shadow 0.15s;
}
.booking-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }

.booking-card-header {
    padding: 12px 14px; display: flex;
    align-items: center; justify-content: space-between;
    border-bottom: 1px solid #f1f5f9; gap: 8px;
}
.booking-card-name { font-size: 14px; font-weight: 700; color: #0f172a; }
.booking-card-sub { font-size: 11px; color: #94a3b8; margin-top: 1px; }

.status-badge {
    padding: 3px 10px; border-radius: 20px;
    font-size: 10px; font-weight: 700; white-space: nowrap; flex-shrink: 0;
}

.booking-card-body {
    padding: 12px 14px;
    display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
}

.info-label { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
.info-value { font-size: 12px; color: #374151; font-weight: 500; margin-top: 2px; }

.booking-card-footer {
    padding: 10px 14px; background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    display: flex; justify-content: space-between;
    align-items: center; font-size: 12px; flex-wrap: wrap; gap: 6px;
}

.menu-list {
    font-size: 11px; color: #64748b;
    padding: 8px 14px; background: #f8fafc;
    border-top: 1px solid #f1f5f9;
}

.date-summary {
    background: linear-gradient(135deg, #fff7ed, #fff);
    border: 1px solid #fed7aa; border-radius: 12px;
    padding: 14px 16px; margin-bottom: 16px;
    display: flex; justify-content: space-between;
    align-items: center; flex-wrap: wrap; gap: 8px;
}
.date-summary-left h3 { font-size: 15px; font-weight: 700; color: #0f172a; }
.date-summary-left p  { font-size: 12px; color: #64748b; margin-top: 2px; }
.date-stats { display: flex; gap: 12px; }
.date-stat { text-align: center; }
.date-stat-num { font-size: 18px; font-weight: 800; color: #f97316; }
.date-stat-lbl { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
</style>

<div x-data="bookingCalendar()" x-init="init()" class="space-y-4">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-4 pt-4 pb-2 border-b border-gray-50">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-sm font-700 text-gray-900 font-bold">Kalender Booking</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Klik tanggal untuk lihat detail</p>
                </div>
                <div class="text-2xl">📅</div>
            </div>
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-dot" style="background:#f97316"></div> Ada booking
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#10b981"></div> Selesai/Disetujui
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#ef4444"></div> Ditolak
                </div>
            </div>
        </div>
        <div class="p-3">
            <div id="calendar"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900" id="detailTitle">Detail Booking</h3>
            <span class="text-xs text-gray-400" id="detailSubtitle">Pilih tanggal di kalender</span>
        </div>
        <div class="p-4" id="bookingDetail">
            <div class="detail-empty">
                <div class="detail-empty-icon">👆</div>
                Klik tanggal di kalender untuk melihat data booking
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script type="application/json" id="booking-events">{!! json_encode($this->events) !!}</script>

<script>
function bookingCalendar() {
    return {
        calendar: null,
        events: [],

        init() {
            this.events = JSON.parse(document.getElementById('booking-events').textContent);
            this.initCalendar();
        },

        getDateStr(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        },

        getBookingsForDate(date) {
            return this.events.filter(e => e.extendedProps.booking_date === date);
        },

        getDateColor(bookings) {
            if (!bookings.length) return null;
            if (bookings.every(b => ['rejected','cancelled'].includes(b.extendedProps.status))) return '#ef4444';
            if (bookings.every(b => ['approved','confirmed','completed'].includes(b.extendedProps.status))) return '#10b981';
            return '#f97316';
        },

        initCalendar() {
            const self = this;
            const el   = document.getElementById('calendar');

            this.calendar = new FullCalendar.Calendar(el, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: { left: 'prev', center: 'title', right: 'next' },
                events: [],

                dayCellDidMount: (info) => {
                    const dateStr = self.getDateStr(info.date);
                    const bookings = self.getBookingsForDate(dateStr);
                    if (!bookings.length) return;

                    const color = self.getDateColor(bookings);
                    info.el.style.backgroundColor = color + '15';
                    info.el.style.borderBottom = '2px solid ' + color;

                    const badge = document.createElement('div');
                    badge.className = 'booking-badge';
                    badge.style.background = color;
                    badge.textContent = bookings.length;
                    info.el.style.position = 'relative';
                    info.el.appendChild(badge);
                },

                dateClick: (info) => {
                    self.showBookings(info.dateStr);
                    document.querySelectorAll('.fc-daygrid-day').forEach(el => el.style.outline = 'none');
                    info.dayEl.style.outline = '2px solid #f97316';
                    info.dayEl.style.outlineOffset = '-2px';
                    info.dayEl.style.borderRadius = '4px';
                },
            });

            this.calendar.render();
        },

        showBookings(dateStr) {
            const bookings = this.getBookingsForDate(dateStr);
            const detail   = document.getElementById('bookingDetail');
            const title    = document.getElementById('detailTitle');
            const subtitle = document.getElementById('detailSubtitle');

            const dateObj  = new Date(dateStr + 'T00:00:00');
            const days     = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months   = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
            const dateDisp = `${days[dateObj.getDay()]}, ${dateObj.getDate()} ${months[dateObj.getMonth()]} ${dateObj.getFullYear()}`;

            title.textContent    = dateDisp;
            subtitle.textContent = bookings.length ? `${bookings.length} booking` : 'Tidak ada booking';

            if (!bookings.length) {
                detail.innerHTML = `<div class="detail-empty"><div class="detail-empty-icon">📭</div>Tidak ada booking pada tanggal ini</div>`;
                return;
            }

            const totalGuests  = bookings.reduce((sum, b) => sum + (b.extendedProps.guest_count || 0), 0);
            const totalApproved = bookings.filter(b => ['approved','confirmed','completed'].includes(b.extendedProps.status)).length;
            const totalCheckin  = bookings.filter(b => b.extendedProps.checkin_status === 'checked_in').length;

            let html = `
                <div class="date-summary">
                    <div class="date-summary-left">
                        <h3>${bookings.length} Reservasi</h3>
                        <p>${totalGuests} tamu total</p>
                    </div>
                    <div class="date-stats">
                        <div class="date-stat">
                            <div class="date-stat-num" style="color:#10b981">${totalApproved}</div>
                            <div class="date-stat-lbl">Disetujui</div>
                        </div>
                        <div class="date-stat">
                            <div class="date-stat-num" style="color:#3b82f6">${totalCheckin}</div>
                            <div class="date-stat-lbl">Check-in</div>
                        </div>
                        <div class="date-stat">
                            <div class="date-stat-num">${bookings.filter(b => b.extendedProps.status === 'pending').length}</div>
                            <div class="date-stat-lbl">Pending</div>
                        </div>
                    </div>
                </div>`;

            bookings.forEach(b => {
                const p = b.extendedProps;

                const statusMap = {
                    'pending'   : { label: '⏳ Pending',      color: '#f97316', bg: '#fff7ed' },
                    'approved'  : { label: '✅ Disetujui',    color: '#3b82f6', bg: '#eff6ff' },
                    'confirmed' : { label: '💜 Dikonfirmasi', color: '#8b5cf6', bg: '#f5f3ff' },
                    'completed' : { label: '🏁 Selesai',      color: '#10b981', bg: '#f0fdf4' },
                    'rejected'  : { label: '❌ Ditolak',      color: '#ef4444', bg: '#fef2f2' },
                    'cancelled' : { label: '🚫 Dibatalkan',   color: '#6b7280', bg: '#f9fafb' },
                };
                const st = statusMap[p.status] || { label: p.status, color: '#6b7280', bg: '#f9fafb' };

                const totalFmt = p.total_price
                    ? 'Rp ' + Number(p.total_price).toLocaleString('id-ID')
                    : '-';

                const checkin = p.checkin_status === 'checked_in'
                    ? `<span style="color:#3b82f6;font-size:11px">✅ Check-in: ${p.checked_in_at || '-'}</span>`
                    : '<span style="color:#94a3b8;font-size:11px">Belum check-in</span>';

                html += `
                <div class="booking-card">
                    <div class="booking-card-header">
                        <div>
                            <div class="booking-card-name">${p.name}</div>
                            <div class="booking-card-sub">📱 ${p.phone}</div>
                        </div>
                        <span class="status-badge" style="background:${st.bg}; color:${st.color}; border:1px solid ${st.color}33">
                            ${st.label}
                        </span>
                    </div>

                    <div class="booking-card-body">
                        <div class="info-item">
                            <div class="info-label">Tempat</div>
                            <div class="info-value">📍 ${p.table || '-'}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Sesi</div>
                            <div class="info-value">🕐 ${p.session !== '-' ? p.session + (p.session_time !== '-' ? ' ('+p.session_time+')' : '') : '-'}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Jam Acara</div>
                            <div class="info-value">⏰ ${p.event_time || '-'}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Makan Siap</div>
                            <div class="info-value">🍽️ ${p.food_ready_time || '-'}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Jumlah Tamu</div>
                            <div class="info-value">👥 ${p.guest_count} orang</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Total</div>
                            <div class="info-value">💰 ${totalFmt}</div>
                        </div>
                    </div>

                    ${p.menus ? `
                    <div class="menu-list">
                        🛒 <strong>Menu:</strong> ${p.menus}
                    </div>` : ''}

                    ${p.note ? `
                    <div class="menu-list" style="border-top:1px solid #f1f5f9">
                        📝 <strong>Catatan:</strong> ${p.note}
                    </div>` : ''}

                    <div class="booking-card-footer">
                        ${checkin}
                    </div>
                </div>`;
            });

            detail.innerHTML = html;
            document.getElementById('bookingDetail').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}
</script>

</x-filament::page>