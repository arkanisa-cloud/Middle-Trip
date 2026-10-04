# Spesifikasi Desain: Modul Detail Gunung (Produk) — MiddleTrip

- **Tanggal**: 2026-09-21
- **Topik**: Detail Gunung & Ekspedisi (Product Detail Page)
- **Status**: Disetujui (Approved)

---

## 1. Latar Belakang & Tujuan

MiddleTrip telah memiliki modul **Home** (Landing Page) dan **Katalog Ekspedisi**. Pengguna membutuhkan halaman detail ekspedisi gunung untuk:
- Mempelajari profil teknis gunung (elevasi MDPL, rute jalur, tingkat kesulitan Grade A/B/C, estimasi durasi, dan suhu).
- Melihat visual jalur dan profil elevasi via diagram kontur/SVG.
- Membaca jadwal itinerary harian pendakian (2D1N).
- Meninjau checklist fasilitas yang termasuk (*Included*) dan tidak termasuk (*Excluded*).
- Memilih opsi pendakian (Camping vs Tek-tok, Jalur, Paket Open/Private) dengan kalkulasi harga interaktif dan direct action reservasi via WhatsApp.

---

## 2. Tipografi Dual-Font (Plus Jakarta Sans & Outfit)

Sesuai arahan desain terbaru, MiddleTrip memadukan dua font utama:
1. **Outfit**:
   - Headline utama (`h1`, `h2`), judul seksi besar.
   - Display angka elevasi (`MDPL`), display harga (`Rp 500.000 / pax`), metrik kuota peserta.
   - Tombol CTA utama (memberikan aksen tebal, berani, dan modern).
2. **Plus Jakarta Sans**:
   - Teks bodi (running text, paragraf deskripsi overview).
   - Label navigasi, breadcrumb, subtitle kartu.
   - Label checklist fasilitas dan catatan rute.

---

## 3. Arsitektur Data & Routing

1. **Routing**:
   - URL: `/ekspedisi/{slug}`
   - Route Name: `ekspedisi.show`
   - Controller: `App\Http\Controllers\ExpeditionController@show`
2. **Data Structure**:
   - Dataset komprehensif di `ExpeditionController` mencakup 6 gunung dari katalog:
     - `mt-merbabu` (Flagship showcase dengan data lengkap sesuai referensi `detail-gunung.html`).
     - `mt-sindoro`, `mt-prau`, `mt-slamet`, `mt-sumbing`, `mt-lawu` (Data terstruktur dan konsisten).
   - Penanganan fallback: `abort(404)` jika slug tidak valid.
3. **Navigasi Silang**:
   - `katalog.blade.php`: Card link dan tombol *"Pilih Trip →"* mengarah ke `route('ekspedisi.show', $expedition['slug'])`.
   - `home.blade.php`: Card Bento Grid mengarah ke `route('ekspedisi.show', $expedition['slug'])`.

---

## 4. Komponen & UI Kit

1. **Header**:
   - Menggunakan Blade component `<x-navbar active="ekspedisi" :hero="false" />`.
2. **Breadcrumb**:
   - `Home > Ekspedisi > {Nama Ekspedisi}`.
3. **Galeri Foto**:
   - 1 foto besar kiri (hero) + 4 foto grid di kanan dengan tombol overlay *"Lihat Foto Asli"*.
4. **Quick Navigation Pills**:
   - Capsule track segmented: `Ringkasan`, `Elevasi & Rute`, `Itinerary`, `Fasilitas`.
5. **Bagian Konten Utama**:
   - **Overview**: Narasi & 4 kartu metrik (Jarak Total, Durasi Waktu, Suhu Rata-rata, Sumber Air).
   - **Elevasi & Rute**: SVG profil elevasi responsif dengan titik Basecamp, Pos 1-3, Sabana, Puncak, serta kartu catatan rute (Titik Air, Terpaan Angin, Sinyal).
   - **Itinerary**: Timeline vertikal 2D1N (Day 1 & Day 2) dengan milestone jam.
   - **Fasilitas**: Komparasi Termasuk vs Tidak Termasuk dengan icon & checkmarks.
6. **Sticky Booking Sidebar**:
   - Tipe Pendakian: Segmented toggle Camping 2D1N vs Tek-tok.
   - Dropdown Jalur: **Mematuhi `docs/UI-kit-dropdown.md` Varian 2** (Custom single-select dengan elevated menu `rounded-2xl`, `border-hairline`, `shadow-xl`, category header uppercase, rotasi chevron `rotate-180`, dan click-outside dismissal).
   - Jenis Paket: Segmented toggle Open Trip vs Private Trip.
   - Kuota peserta: Progress bar kapasitas.
   - Kalkulasi Harga Dinamis: Menyesuaikan harga dasar sesuai tipe dan paket yang dipilih.
   - Tombol Booking: Membuka modal konfirmasi dengan detail pesanan dan direct link WhatsApp CS MiddleTrip.

---

## 5. Dokumentasi yang Diselaraskan

1. `docs/DESIGN.md`: Memperbarui bagian tipografi untuk mencakup dual-font system (Plus Jakarta Sans + Outfit) dan spesifikasi modul detail.
2. `docs/README.md`: Memperbarui tabel teknologi terkait tipografi ganda.
