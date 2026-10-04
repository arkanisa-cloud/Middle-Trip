# Detail Gunung (Produk) Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun modul Detail Gunung (Produk) lengkap di MiddleTrip dengan integrasi data dinamis, kombinasi tipografi Plus Jakarta Sans & Outfit, custom dropdown sesuai `UI-kit-dropdown.md`, diagram elevasi SVG, timeline itinerary, komparasi fasilitas, sticky booking card interaktif, dan pembaruan dokumentasi `.md`.

**Architecture:** Modul detail diimplementasikan via method `show(string $slug)` pada `ExpeditionController` yang menyajikan dataset ekspedisi komprehensif ke view `resources/views/detail.blade.php`. Tampilan mengadopsi struktur `detail-gunung.html` yang diselaraskan dengan token `DESIGN.md` dan komponen `<x-navbar active="ekspedisi" :hero="false" />`. Dropdown jalur diimplementasikan sesuai `UI-kit-dropdown.md` Varian 2. Kartu ekspedisi di katalog dan home dihubungkan ke rute detail ini.

**Tech Stack:** Laravel 13, PHP 8.5, Tailwind CSS v4, Blade Templating, Google Fonts (Plus Jakarta Sans & Outfit), PHPUnit 12, Laravel Pint.

**Spec:** [`docs/superpowers/specs/2026-09-21-detail-gunung-design.md`](docs/superpowers/specs/2026-09-21-detail-gunung-design.md)

## Global Constraints

- Standar PHP 8: constructor property promotion, explicit return types dan parameter type hinting, kurung kurawal `{}` untuk semua struktur kontrol, TitleCase untuk enum.
- Format kode menggunakan Laravel Pint (`php artisan pint --dirty` atau `./vendor/bin/sail pint`).
- Tipografi: Kombinasi **Plus Jakarta Sans** (teks isi, narasi, label, checklist) dan **Outfit** (judul utama, angka MDPL, display harga, tombol CTA).
- Dropdown harus mematuhi panduan di [`docs/UI-kit-dropdown.md`](docs/UI-kit-dropdown.md) Varian 2.
- Desain warna dan badge harus mematuhi token di [`docs/DESIGN.md`](docs/DESIGN.md) (Terracotta `#A0401C`, Canvas `#F8F9FA`, Grade A Emerald, Grade B Amber, Grade C Rose).

---

### Task 1: Update Dokumentasi Desain & Petunjuk Pengembang

**Files:**
- Modify: `docs/DESIGN.md`
- Modify: `docs/README.md`

**Interfaces:**
- Menyelaraskan spesifikasi tipografi ganda (Plus Jakarta Sans & Outfit) dan mencatat modul Detail Gunung.

- [ ] **Step 1: Edit `docs/DESIGN.md`**
Tambahkan penjelasan tipografi ganda (Plus Jakarta Sans untuk editorial/body/UI dan Outfit untuk display headings/prices/metrics), serta dokumentasikan komponen halaman detail produk.

- [ ] **Step 2: Edit `docs/README.md`**
Perbarui tabel arsitektur & tech stack bagian tipografi dan peta fitur agar mencakup modul Detail Gunung dan font Outfit.

---

### Task 2: Update Dataset & Method `show` pada `ExpeditionController`

**Files:**
- Modify: `app/Http/Controllers/ExpeditionController.php`
- Create: `tests/Feature/ExpeditionDetailTest.php`

**Interfaces:**
- Consumes: Request parameter `string $slug` dari rute.
- Produces: `show(string $slug): View` yang mengirimkan variabel `$expedition` ke view `detail`.

- [ ] **Step 1: Tulis test kasus untuk rute detail ekspedisi**
Buat `tests/Feature/ExpeditionDetailTest.php` yang menguji:
1. `GET /ekspedisi/mt-merbabu` mengembalikan status 200 dan melihat teks "Mt. Merbabu Expedition".
2. `GET /ekspedisi/slug-tidak-ada` mengembalikan status 404.

- [ ] **Step 2: Jalankan test untuk memverifikasi kegagalan**
Jalankan `php artisan test --filter=ExpeditionDetailTest`
Ekspektasi: FAIL (Method `show` atau route belum ada).

- [ ] **Step 3: Implementasikan dataset komprehensif dan method `show` di `ExpeditionController`**
Tambahkan key `slug` pada setiap item ekspedisi di `ExpeditionController`. Sediakan data lengkap untuk `mt-merbabu` (galeri foto, stat overview, titik elevasi SVG, peringatan jalur, timeline itinerary 2D1N, fasilitas include & exclude, daftar jalur via Selo, Suwanting, Thekelan, Wekas, kuota peserta). Sediakan pula data fallback terstruktur untuk 5 ekspedisi lainnya. Implementasikan method `show(string $slug): View` dengan `abort(404)` jika slug tidak ditemukan.

- [ ] **Step 4: Jalankan test untuk memverifikasi keberhasilan**
Jalankan `php artisan test --filter=ExpeditionDetailTest`
Ekspektasi: PASS.

---

### Task 3: Pendaftaran Rute Ekspedisi Detail

**Files:**
- Modify: `routes/web.php`

**Interfaces:**
- Menambahkan `Route::get('/ekspedisi/{slug}', [ExpeditionController::class, 'show'])->name('ekspedisi.show');`.

- [ ] **Step 1: Daftarkan rute di `routes/web.php`**
- [ ] **Step 2: Verifikasi daftar rute**
Jalankan `php artisan route:list --name=ekspedisi` dan pastikan `ekspedisi.show` terdaftar dengan parameter `{slug}`.

---

### Task 4: Bangun Tampilan Halaman Detail Gunung (`resources/views/detail.blade.php`)

**Files:**
- Create: `resources/views/detail.blade.php`

**Interfaces:**
- Consumes: `$expedition` (array dengan detail gunung).
- Komponen Navbar: `<x-navbar active="ekspedisi" :hero="false" />`.
- Dropdown Jalur: Standar `docs/UI-kit-dropdown.md` Varian 2.

- [ ] **Step 1: Susun template dasar & layout header**
Gunakan Plus Jakarta Sans & Outfit dari Google Fonts. Sertakan `<x-navbar active="ekspedisi" :hero="false" />`, breadcrumbs, judul halaman dalam font Outfit, dan meta badges (MDPL, Grade kesulitan semantik, Lokasi).

- [ ] **Step 2: Implementasikan Galeri Foto & Modal Preview**
Grid 1 foto hero utama + 4 foto grid di kanan dengan badge/tombol overlay "Lihat Foto Asli" dan modal preview foto responsif.

- [ ] **Step 3: Implementasikan Segmented Navigation Pills & Section Konten**
- Segmented pills mengambang (Ringkasan, Elevasi & Rute, Itinerary, Fasilitas) dengan navigasi smooth scroll.
- **Section Overview**: Paragraf dan 4 kotak metrik (Jarak Total, Durasi Waktu, Suhu, Sumber Air).
- **Section Elevasi & Rute**: SVG kurva profil elevasi interaktif/responsif dengan titik basecamp, pos-pos, sabana, puncak, serta alert badges (Titik Air, Zona Angin, Sinyal Seluler).
- **Section Itinerary**: Timeline vertikal 2D1N (Day 1 & Day 2) dengan marker waktu dan kegiatan.
- **Section Fasilitas**: Komparasi 2 card (Termasuk / Included warna hijau & Tidak Termasuk / Exclude warna merah).

- [ ] **Step 4: Implementasikan Sticky Booking Sidebar & Custom Dropdown**
- Segmented control Tipe Pendakian (Camping 2D1N vs Tek-tok).
- **Custom Dropdown Jalur (Via)** mematuhi `docs/UI-kit-dropdown.md` Varian 2: container elevated menu `rounded-2xl`, `border-hairline`, `shadow-xl`, category header uppercase, chevron rotation `rotate-180`, input hidden terikat, dan click-outside dismissal.
- Segmented control Jenis Paket (Open Trip vs Private Trip).
- Kuota peserta tersisa dengan progress bar.
- Kalkulator harga dinamis (font Outfit) yang otomatis menyesuaikan saat tipe pendakian atau paket diganti.
- Tombol "Booking Sekarang" yang memicu modal konfirmasi MiddleTrip lengkap dengan rincian pesanan dan tombol WhatsApp resmi.

- [ ] **Step 5: Sertakan Footer MiddleTrip**
Sertakan footer konsisten dengan layout katalog dan home.

---

### Task 5: Hubungkan Navigasi Katalog & Home ke Halaman Detail

**Files:**
- Modify: `resources/views/katalog.blade.php`
- Modify: `resources/views/home.blade.php`

**Interfaces:**
- Tombol "Pilih Trip →", gambar cover, dan judul ekspedisi mengarahkan ke `route('ekspedisi.show', $expedition['slug'])`.

- [ ] **Step 1: Update `resources/views/katalog.blade.php`**
Arahkan kartu ekspedisi ke `route('ekspedisi.show', $expedition['slug'])`.

- [ ] **Step 2: Update `resources/views/home.blade.php`**
Arahkan kartu bento grid ke `route('ekspedisi.show', 'mt-merbabu')` dan kartu lainnya ke slug masing-masing.

---

### Task 6: Verifikasi, Format Kode & Build Aset

**Files:**
- Run automated tests: `php artisan test`
- Run Pint formatter: `php artisan pint --dirty`
- Run asset build: `npm run build`

- [ ] **Step 1: Jalankan PHPUnit**
Pastikan semua test lulus 100%.

- [ ] **Step 2: Jalankan Laravel Pint**
Pastikan standar PSR-12 dan konvensi Laravel terpenuhi.

- [ ] **Step 3: Jalankan Vite Production Build**
Pastikan tidak ada error kompilasi CSS maupun JS.
