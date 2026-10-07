import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

document.addEventListener("DOMContentLoaded", () => {
    const track = document.querySelector("#highlightTrack");
    if (!track || track.children.length === 0) return;

    let cards = [...track.children];
    
// Konfigurasi
const VISIBLE = 3;          // Jumlah kartu virtual (untuk perhitungan clone)
const STEP_DELAY = 2200;    // Durasi pause per produk (sebelumnya 3000ms)
const SPEED = 500;          // Kecepatan animasi geser (sebelumnya 650ms)

    let index = VISIBLE;
    let paused = false;
    let isSnapping = false;
    let autoplayTimeout = null;

    // 1. CLONE ELEMEN UNTUK INFINITE LOOP
    const head = cards.slice(0, VISIBLE).map(n => n.cloneNode(true));
    const tail = cards.slice(-VISIBLE).map(n => n.cloneNode(true));

    head.forEach(n => n.dataset.clone = "head");
    tail.forEach(n => n.dataset.clone = "tail");

    tail.forEach(n => track.insertBefore(n, cards[0]));
    head.forEach(n => track.appendChild(n));

    cards = [...track.children];

    // 2. FUNGSI PERGERAKAN
function goTo(i, animated = true) {
    if (!cards[i]) return;

    const GAP = 10; // jarak kiri-kanan

    const x = cards[i].offsetLeft - GAP;

    track.style.transition = animated
        ? `transform ${SPEED}ms ease`
        : "none";

    track.style.transform = `translateX(-${Math.max(0, x)}px)`;
}

    function normalize(i) {
        if (cards[i]?.dataset.clone === "head") return VISIBLE;
        if (cards[i]?.dataset.clone === "tail") return cards.length - (VISIBLE * 2 + 1);
        return i;
    }

    // 3. INISIALISASI
    function init() {
        if (!cards[index] || cards[index].offsetWidth === 0) {
            return requestAnimationFrame(init);
        }
        goTo(index, false);
    }
    
    requestAnimationFrame(init);
    window.addEventListener("load", () => goTo(index, false));
    window.addEventListener("resize", () => goTo(index, false));

    // 4. AUTOPLAY
    function scheduleNext() {
        clearTimeout(autoplayTimeout);
        autoplayTimeout = setTimeout(() => {
            if (paused || isSnapping) return scheduleNext();
            
            index++;
            goTo(index, true);

            setTimeout(() => {
                if (cards[index]?.dataset.clone) {
                    index = normalize(index);
                    goTo(index, false);
                }
                scheduleNext();
            }, SPEED + 50);

        }, STEP_DELAY);
    }
    scheduleNext();

    track.addEventListener("transitionend", (e) => {
        if (e.propertyName !== "transform") return;
        if (isSnapping) return;
        if (cards[index]?.dataset.clone) {
            index = normalize(index);
            goTo(index, false);
        }
    });

    // 5. INTERAKSI PENGGUNA (PAUSE & SNAP)
    function pauseAndSnap() {
        if (isSnapping) return;
        paused = true;
        isSnapping = true;

        let nearest = 0, min = Infinity;
        const transformValue = track.style.transform || "translateX(0px)";
        const t = Math.abs(parseFloat(transformValue.replace(/[^\d.]/g, "")) || 0);

        cards.forEach((c, i) => {
            const d = Math.abs(t - c.offsetLeft);
            if (d < min) { min = d; nearest = i; }
        });

        index = nearest;
        goTo(index, true);

        setTimeout(() => {
            index = normalize(index);
            goTo(index, false);
            isSnapping = false;
            paused = false;
            scheduleNext();
        }, SPEED);
    }

    // 6. DRAG, SWIPE & WHEEL LOGIC
    let startX = 0, dragSum = 0;
    const THRESH = 90;

    function step(dir) {
        index += dir;
        goTo(index, true);
    }

    // Mouse
    track.addEventListener("mousedown", e => {
        startX = e.clientX; dragSum = 0; pauseAndSnap();
    });
    track.addEventListener("mousemove", e => {
        if (e.buttons !== 1) return;
        const dx = e.clientX - startX;
        startX = e.clientX; dragSum += dx;
        if (dragSum < -THRESH) { step(1); dragSum = 0; }
        if (dragSum >  THRESH) { step(-1); dragSum = 0; }
    });

    // Touch (HP)
    track.addEventListener("touchstart", e => {
        startX = e.touches[0].clientX; dragSum = 0; pauseAndSnap();
    }, { passive: true });
    
    track.addEventListener("touchmove", e => {
        const dx = e.touches[0].clientX - startX;
        startX = e.touches[0].clientX; dragSum += dx;
        if (dragSum < -THRESH) { step(1); dragSum = 0; }
        if (dragSum >  THRESH) { step(-1); dragSum = 0; }
    }, { passive: true });
});

function sendToWhatsApp() {
    // 1. Ambil nilai dari inputan
    const name = document.getElementById('waName').value;
    const email = document.getElementById('waEmail').value;
    const message = document.getElementById('waMessage').value;

    // 2. Validasi sederhana (pastikan nama dan pesan tidak kosong)
    if (!name || !message) {
        alert("Mohon lengkapi Nama dan Pesan Anda terlebih dahulu.");
        return;
    }

    // 3. Ambil nomor WhatsApp dari database Anda
    // Menggunakan regex bawaan PHP untuk memastikan hanya angka yang masuk
    const waNumber = "{{ preg_replace('/[^0-9]/', '', optional($setting)->whatsapp) }}";

    if (!waNumber) {
        alert("Nomor WhatsApp belum dikonfigurasi oleh admin.");
        return;
    }

    // 4. Buat format teks untuk pesan WhatsApp
    const text = `Halo, saya ingin bertanya/menghubungi Anda.\n\n*Nama:* ${name}\n*Email:* ${email || '-'}\n*Pesan:*\n${message}`;

    // 5. Ubah teks menjadi format URL agar aman dikirim via browser
    const encodedText = encodeURIComponent(text);

    // 6. Buka tab baru menuju WhatsApp
    const waUrl = `https://wa.me/${waNumber}?text=${encodedText}`;
    window.open(waUrl, '_blank');
}

// Reveal animation untuk header "Today's Special" + tombol menu
document.addEventListener("DOMContentLoaded", () => {
    const revealItems = document.querySelectorAll(
        "#special-badge, #special-title, #special-divider, #special-cta"
    );
    if (!revealItems.length) return;

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add("is-visible"), i * 120);
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2, rootMargin: "0px 0px -60px 0px" });

    revealItems.forEach((el) => revealObserver.observe(el));
});