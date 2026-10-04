# Route-Based Pricing Specification & Design

## 1. Overview
Fitur **Route-Based Pricing** memungkinkan harga ekspedisi (Open Trip & Private Trip, baik kategori Camping maupun Tektok) dikonfigurasi per masing-masing jalur pendakian (Route / Via) dalam satu gunung.

Saat pengunjung memilih via yang berbeda di halaman detail, harga yang ditampilkan di halaman utama dan di modal booking akan langsung ter-update secara otomatis tanpa reload halaman.

Di katalog dan halaman utama (Home), harga yang ditampilkan untuk setiap gunung menggunakan strategi **Opsi A: "Mulai dari Rp [Harga Via Termurah]"**.

---

## 2. Database Schema Changes
Tabel `routes` ditambahkan 4 kolom harga bertipe `unsignedInteger` dan bersifat `nullable`:

| Kolom | Tipe | Default | Deskripsi |
|---|---|---|---|
| `price_camping_open` | `unsignedInteger` | `nullable` | Harga per pax untuk Open Trip Camping di via ini |
| `price_tektok_open` | `unsignedInteger` | `nullable` | Harga per pax untuk Open Trip Tektok di via ini |
| `price_camping_private` | `unsignedInteger` | `nullable` | Harga per pax untuk Private Trip Camping di via ini |
| `price_tektok_private` | `unsignedInteger` | `nullable` | Harga per pax untuk Private Trip Tektok di via ini |

### Fallback Mechanism:
Jika kolom harga pada `routes` bernilai `null` (misalnya pada data lama), sistem akan fallback secara mulus ke harga bawaan gunung (`Mountain` base price / effective price).

---

## 3. Eloquent Model Updates

### `App\Models\Route`:
- Casts:
  - `price_camping_open => 'integer'`
  - `price_tektok_open => 'integer'`
  - `price_camping_private => 'integer'`
  - `price_tektok_private => 'integer'`
- Helper Accessors:
  - `getEffectivePriceCampingOpenAttribute()`
  - `getEffectivePriceTektokOpenAttribute()`
  - `getEffectivePriceCampingPrivateAttribute()`
  - `getEffectivePriceTektokPrivateAttribute()`

### `App\Models\Mountain`:
- Tambah accessor `getEffectiveStartingPriceAttribute()`:
  - Mengambil nilai `min(price_camping_open)` dari relasi `routes` aktif yang memiliki harga > 0.
  - Fallback ke `$this->base_price`.
- Update `getFormattedPriceAttribute()` & `getFormattedShortPriceAttribute()` untuk menggunakan `effective_starting_price` (Opsi A).

---

## 4. Admin Management (CMS)
- **Form Create & Edit Gunung** ([`create.blade.php`](file:///home/alvaro/Documents/SMK%20XII/Project/MiddleTrip/resources/views/admin/mountains/create.blade.php) & [`edit.blade.php`](file:///home/alvaro/Documents/SMK%20XII/Project/MiddleTrip/resources/views/admin/mountains/edit.blade.php)):
  - Pada accordion/tab Jalur Pendakian (`routes`), tambahkan 4 input field harga (Open Camping, Open Tektok, Private Camping, Private Tektok) dengan format Rupiah yang rapi dan serasi dengan design system STS Admin.
- **Controller** ([`MountainController.php`](file:///home/alvaro/Documents/SMK%20XII/Project/MiddleTrip/app/Http/Controllers/Admin/MountainController.php)):
  - Validasi `routes.*.price_camping_open`, `routes.*.price_tektok_open`, dll.
  - Simpan dan sinkronkan data harga ke record `Route`.

---

## 5. Customer Detail Page & Reactive UI
- **Controller** ([`ExpeditionController.php`](file:///home/alvaro/Documents/SMK%20XII/Project/MiddleTrip/app/Http/Controllers/ExpeditionController.php)):
  - Mengirimkan 4 harga untuk tiap route dalam array `expeditionData.routes`.
- **View & JS** ([`detail.blade.php`](file:///home/alvaro/Documents/SMK%20XII/Project/MiddleTrip/resources/views/customer/detail.blade.php)):
  - `calculateCurrentPrice()` membaca route yang sedang aktif (`selectedRouteId`).
  - Saat `selectJalurOption()` dipanggil, langsung memanggil `updatePriceDisplay()` sehingga display harga di banner, floating summary, dan modal booking langsung update instan.
  - Alpine.js modal booking mendeteksi perubahan via dan meng-update harga satuan dan grand total.

---

## 6. Booking Service & Pricing Integrity
- **[`BookingService.php`](file:///home/alvaro/Documents/SMK%20XII/Project/MiddleTrip/app/Services/BookingService.php)**:
  - Mengambil model `Route` berdasarkan `data['route_id']`.
  - Menggunakan harga dari route tersebut sesuai kombinasi `trip_type` (open/private) dan `hiking_type` (camping/tektok).
  - Menghitung grand total dan menyimpan `locked_price_per_pax` secara akurat.

---

## 7. Testing Strategy
- Feature test:
  - Verifikasi migrasi kolom harga pada rute.
  - Verifikasi kalkulasi harga per via di `Mountain` dan `Route`.
  - Verifikasi `BookingService` menggunakan harga dari rute yang dipilih.
  - Verifikasi Admin dapat menyimpan rute dengan harga masing-masing.
