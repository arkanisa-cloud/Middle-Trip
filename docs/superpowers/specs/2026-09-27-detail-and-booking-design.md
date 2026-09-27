# Technical Design Spec: Detail Page & Booking Subsystem (MiddleTrip)

## 1. Overview & System Purpose
Dokumen ini mendefinisikan arsitektur teknis, skema database, logika perhitungan, serta interaksi antarmuka Alpine.js untuk halaman **Detail Ekspedisi Gunung** (`/ekspedisi/{slug}`) dan **Modal Pemesanan Tiket** (`#bookingModal`) pada platform MiddleTrip.

---

## 2. Core Business Rules

### 2.1 Pricing Tiering & Dynamic Discount
* Harga per pax untuk **Open Trip** dan **Private Trip** bersifat dinamis berdasarkan matriks kuota pendaftar (`expedition_price_tiers`).
* Pada **Open Trip**, harga per pax berkurang seiring bertambahnya total akumulasi peserta yang mendaftar dalam satu batch trip.
* Pada **Private Trip**, pemesan langsung menentukan jumlah peserta dan tanggal sendiri; harga per pax dihitung otomatis berdasarkan tiering jumlah peserta rombongan tersebut dan diwajibkan melunasi 100% di awal.

### 2.2 Alur Pemesanan 4 Tahap (Open Trip)
1. **OPEN**: User menginput data pemesan (Ketua) dan NIK + Nama seluruh peserta anggota rombongan. User membayar **Booking Fee (DP)** sebesar `pax_count * mountain.booking_fee_per_pax`.
2. **RESERVED**: Setelah DP lunas, booking berstatus `reserved`. User dimasukkan ke grup WhatsApp untuk koordinasi dan menunggu jadwal penutupan batch/Price Lock.
3. **PRICE LOCK**: Pada H-X sebelum keberangkatan (`mountain.price_lock_days_before_departure`, misal H-3), sistem Laravel Job memproses Price Lock otomatis. Sistem mengunci harga akhir berdasarkan total akumulasi peserta batch dan mengkalkulasi sisa pelunasan (`remaining_payment_total`). User diberikan waktu 48 jam untuk pelunasan. Jika gagal melunasi, status menjadi `expired` dan slot dibuka kembali.
4. **PAID**: Sisa tagihan lunas, status menjadi `paid`. Trip dipastikan siap berangkat terlepas dari berapa pun jumlah peserta yang terkumpul (tidak ada minimal keberangkatan).

---

## 3. Database Architecture & Models

### 3.1 Tables Schema Specification

#### `mountains`
- `id` (bigint, primary key)
- `name` (string)
- `slug` (string, unique)
- `elevation` (integer, e.g. 3142)
- `province` (string)
- `cover_image` (string)
- `description` (text, nullable)
- `booking_fee_per_pax` (unsignedInteger, default 150000)
- `price_lock_days_before_departure` (unsignedTinyInteger, default 3)
- `is_active` (boolean, default true)
- `timestamps`

#### `expedition_price_tiers`
- `id` (bigint, primary key)
- `mountain_id` (foreignId -> mountains)
- `min_pax` (unsignedInteger)
- `max_pax` (unsignedInteger)
- `price_per_pax` (unsignedInteger)
- `timestamps`

#### `expeditions`
- `id` (bigint, primary key)
- `mountain_id` (foreignId -> mountains)
- `route_id` (foreignId -> routes)
- `type` (enum: `'open'`, `'private'`)
- `hiking_type` (enum: `'camping'`, `'tektok'`)
- `departure_date` (date)
- `return_date` (date)
- `quota_max` (unsignedInteger)
- `quota_booked` (unsignedInteger, default 0)
- `current_locked_price` (unsignedInteger, nullable)
- `status` (enum: `'open'`, `'price_locked'`, `'completed'`, `'cancelled'`, default `'open'`)
- `timestamps`

#### `bookings`
- `id` (bigint, primary key)
- `booking_code` (string, unique, e.g. `MT-20260815-XY89`)
- `user_id` (foreignId -> users, nullable)
- `expedition_id` (foreignId -> expeditions)
- `route_id` (foreignId -> routes)
- `meeting_point_id` (foreignId -> meeting_points, nullable)
- `customer_name` (string)
- `customer_email` (string)
- `customer_phone` (string)
- `pax_count` (unsignedInteger)
- `booking_fee_per_pax` (unsignedInteger)
- `total_booking_fee` (unsignedInteger) -> Wajib dibayar di awal
- `shuttle_fee_total` (unsignedInteger, default 0)
- `addons_fee_total` (unsignedInteger, default 0)
- `locked_price_per_pax` (unsignedInteger, nullable)
- `remaining_payment_total` (unsignedInteger, nullable)
- `status` (enum: `'open'`, `'reserved'`, `'price_locked'`, `'paid'`, `'expired'`, `'cancelled'`, default `'open'`)
- `payment_deadline` (dateTime, nullable)
- `timestamps`

#### `booking_participants`
- `id` (bigint, primary key)
- `booking_id` (foreignId -> bookings)
- `full_name` (string)
- `nik` (string)
- `is_leader` (boolean, default false)
- `timestamps`

#### `meeting_points`
- `id` (bigint, primary key)
- `mountain_id` (foreignId -> mountains)
- `name` (string)
- `additional_price_per_pax` (unsignedInteger, default 0)
- `is_default` (boolean, default false)
- `timestamps`

#### `addons`
- `id` (bigint, primary key)
- `name` (string)
- `price` (unsignedInteger)
- `is_active` (boolean, default true)
- `timestamps`

#### `booking_addons`
- `booking_id` (foreignId -> bookings)
- `addon_id` (foreignId -> addons)
- `price` (unsignedInteger)

---

## 4. State Management (Alpine.js Frontend Integration)

### 4.1 Component Architecture (`bookingModalComponent`)
Seluruh interaksi di `resources/views/customer/detail.blade.php` dikelola oleh Alpine.js:
- **Pax Counter Stepper**: Menyesuaikan jumlah peserta (1 s/d kuota tersisa) dan menambahkan/mengarahkan form input Nama & NIK secara dinamis.
- **Meeting Point Selector**: Menghitung estimasi biaya shuttle per pax.
- **Addon Checkboxes**: Menghitung estimasi biaya perlengkapan tambahan.
- **Dynamic Initial Price Display**:
  - Direct DP Payable: `pax_count * mountain.booking_fee_per_pax`
  - Total Estimated Trip Cost breakdown: `(basePrice * pax_count) + (shuttle * pax_count) + addons`

---

## 5. Automated Price Lock Job (`expeditions:process-price-locks`)

Command Artisan terjadwal harian:
1. Mencari seluruh `expeditions` dengan `status = 'open'` yang `departure_date - mountain.price_lock_days_before_departure <= today()`.
2. Menghitung akumulasi total `quota_booked`.
3. Mencari `price_per_pax` dari `expedition_price_tiers` yang sesuai dengan `quota_booked`.
4. Mengunci `expeditions.current_locked_price` dan mengubah `expeditions.status = 'price_locked'`.
5. Untuk setiap `booking` terasosiasi dengan `status = 'reserved'`:
   - Set `locked_price_per_pax = locked_price`.
   - Hitung `remaining_payment_total = (pax_count * locked_price) + shuttle_fee_total + addons_fee_total - total_booking_fee`.
   - Set `status = 'price_locked'` dan `payment_deadline = now() + 48 hours`.
