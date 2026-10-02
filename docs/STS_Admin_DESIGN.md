---
version: alpha
name: STS-admin-vault-design-system
description: Design system resmi panel administrasi STS (Streetwear to Santri). Mengadopsi arsitektur visual Brutalist-Minimalism / Modern Vault dengan kontras monokromatik tinggi (Zinc-950, Zinc-900, Zinc-50, Pure White), tipografi tajam Figtree beraksen italic font-black dan wide tracking, serta komponen terstruktur untuk efisiensi pengelolaan e-commerce retail streetwear.

colors:
  primary: "#09090B"
  primary-hover: "#18181B"
  primary-active: "#27272A"
  primary-subtle: "#F4F4F5"
  sidebar-bg: "#09090B"
  sidebar-border: "#18181B"
  sidebar-text: "#A1A1AA"
  sidebar-text-active: "#FFFFFF"
  sidebar-active-bg: "#18181B"
  sidebar-section-header: "#52525B"
  canvas: "#FAFAFA"
  canvas-subtle: "#F4F4F5"
  workspace-bg: "#F9FAFB"
  surface-card: "#FFFFFF"
  surface-subtle: "#F4F4F5"
  hairline: "#E4E4E7"
  hairline-soft: "#F4F4F5"
  hairline-strong: "#D4D4D8"
  ink-heading: "#09090B"
  ink-body: "#18181B"
  body-strong: "#27272A"
  body: "#52525B"
  muted: "#71717A"
  muted-soft: "#A1A1AA"
  on-primary: "#FFFFFF"
  on-dark: "#FFFFFF"
  on-dark-soft: "#A1A1AA"
  status-pending-bg: "#F4F4F5"
  status-pending-text: "#52525B"
  status-pending-border: "#E4E4E7"
  status-processed-bg: "#09090B"
  status-processed-text: "#FFFFFF"
  status-processed-border: "#09090B"
  status-shipped-bg: "#EFF6FF"
  status-shipped-text: "#2563EB"
  status-shipped-border: "#DBEAFE"
  status-completed-bg: "#ECFDF5"
  status-completed-text: "#059669"
  status-completed-border: "#A7F3D0"
  status-cancelled-bg: "#FFF1F2"
  status-cancelled-text: "#E11D48"
  status-cancelled-border: "#FFE4E6"
  stock-critical-bg: "#FEF2F2"
  stock-critical-text: "#DC2626"
  stock-normal-bg: "#F4F4F5"
  stock-normal-text: "#52525B"
  success: "#059669"
  warning: "#D97706"
  error: "#DC2626"
  info: "#2563EB"

typography:
  display-xl:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 32px
    fontWeight: 900
    lineHeight: 1.15
    letterSpacing: -0.04em
    textTransform: uppercase
    fontStyle: italic
  display-lg:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 24px
    fontWeight: 900
    lineHeight: 1.2
    letterSpacing: -0.03em
    textTransform: uppercase
    fontStyle: italic
  display-md:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 20px
    fontWeight: 900
    lineHeight: 1.25
    letterSpacing: -0.025em
    textTransform: uppercase
    fontStyle: italic
  display-sm:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 18px
    fontWeight: 800
    lineHeight: 1.3
    letterSpacing: -0.02em
    textTransform: uppercase
  title-lg:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 16px
    fontWeight: 800
    lineHeight: 1.35
    letterSpacing: -0.01em
  title-md:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 14px
    fontWeight: 700
    lineHeight: 1.4
    letterSpacing: -0.01em
  title-sm:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 13px
    fontWeight: 700
    lineHeight: 1.4
    letterSpacing: 0
  body-md:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 14px
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: 0
  body-sm:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 12px
    fontWeight: 500
    lineHeight: 1.5
    letterSpacing: 0
  table-header:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 11px
    fontWeight: 900
    lineHeight: 1.4
    letterSpacing: 0.1em
    textTransform: uppercase
  label-micro:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 9px
    fontWeight: 900
    lineHeight: 1.3
    letterSpacing: 0.2em
    textTransform: uppercase
  nav-section:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 9px
    fontWeight: 800
    lineHeight: 1.3
    letterSpacing: 0.2em
    textTransform: uppercase
  nav-link:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 11px
    fontWeight: 600
    lineHeight: 1.4
    letterSpacing: 0.15em
    textTransform: uppercase
  button:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 11px
    fontWeight: 800
    lineHeight: 1
    letterSpacing: 0.15em
    textTransform: uppercase
  badge-status:
    fontFamily: '"Figtree", sans-serif'
    fontSize: 10px
    fontWeight: 900
    lineHeight: 1.2
    letterSpacing: 0.08em
    textTransform: uppercase
    fontStyle: italic

rounded:
  xs: 4px
  sm: 6px
  md: 8px
  lg: 12px
  xl: 16px
  2xl: 20px
  3xl: 24px
  full: 9999px

spacing:
  xxs: 4px
  xs: 8px
  sm: 12px
  md: 16px
  lg: 24px
  xl: 32px
  xxl: 40px
  section: 48px

components:
  sidebar-container:
    backgroundColor: "{colors.sidebar-bg}"
    width: 256px
    borderRight: "1px solid {colors.sidebar-border}"
    textColor: "{colors.sidebar-text}"

  sidebar-item-active:
    backgroundColor: "{colors.sidebar-active-bg}"
    textColor: "{colors.sidebar-text-active}"
    typography: "{typography.nav-link}"
    rounded: "{rounded.md}"
    padding: 12px 16px

  sidebar-item-inactive:
    backgroundColor: transparent
    textColor: "{colors.sidebar-text}"
    typography: "{typography.nav-link}"
    rounded: "{rounded.md}"
    padding: 12px 16px

  top-header-bar:
    backgroundColor: "{colors.surface-card}"
    height: 80px
    borderBottom: "1px solid {colors.hairline}"
    padding: 0 40px

  metric-stat-card:
    backgroundColor: "{colors.surface-card}"
    rounded: "{rounded.2xl}"
    padding: 24px
    border: "1px solid {colors.hairline-soft}"
    shadow: "0 1px 2px rgba(0, 0, 0, 0.05)"
    hoverBorder: "{colors.primary}"

  table-container-card:
    backgroundColor: "{colors.surface-card}"
    rounded: "{rounded.2xl}"
    border: "1px solid {colors.hairline-soft}"
    shadow: "0 1px 3px rgba(0, 0, 0, 0.04)"
    overflow: hidden

  datatable-header-row:
    backgroundColor: "{colors.canvas}"
    borderBottom: "1px solid {colors.hairline}"
    typography: "{typography.table-header}"
    textColor: "{colors.body}"

  button-primary-action:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    typography: "{typography.button}"
    rounded: "{rounded.md}"
    padding: 10px 20px
    hoverBackgroundColor: "{colors.primary-hover}"
    transition: "all 0.2s ease"

  button-secondary-outline:
    backgroundColor: "{colors.surface-card}"
    textColor: "{colors.ink-heading}"
    border: "1px solid {colors.hairline}"
    typography: "{typography.button}"
    rounded: "{rounded.md}"
    padding: 8px 16px
    hoverBorderColor: "{colors.primary}"

  button-danger-ghost:
    backgroundColor: transparent
    textColor: "{colors.muted-soft}"
    rounded: "{rounded.md}"
    padding: 8px
    hoverTextColor: "{colors.error}"

  status-badge-pending:
    backgroundColor: "{colors.status-pending-bg}"
    textColor: "{colors.status-pending-text}"
    border: "1px solid {colors.status-pending-border}"
    typography: "{typography.badge-status}"
    rounded: "{rounded.xs}"
    padding: 4px 12px

  status-badge-processed:
    backgroundColor: "{colors.status-processed-bg}"
    textColor: "{colors.status-processed-text}"
    border: "1px solid {colors.status-processed-border}"
    typography: "{typography.badge-status}"
    rounded: "{rounded.xs}"
    padding: 4px 12px

  status-badge-shipped:
    backgroundColor: "{colors.status-shipped-bg}"
    textColor: "{colors.status-shipped-text}"
    border: "1px solid {colors.status-shipped-border}"
    typography: "{typography.badge-status}"
    rounded: "{rounded.xs}"
    padding: 4px 12px

  status-badge-completed:
    backgroundColor: "{colors.status-completed-bg}"
    textColor: "{colors.status-completed-text}"
    border: "1px solid {colors.status-completed-border}"
    typography: "{typography.badge-status}"
    rounded: "{rounded.xs}"
    padding: 4px 12px

  status-badge-cancelled:
    backgroundColor: "{colors.status-cancelled-bg}"
    textColor: "{colors.status-cancelled-text}"
    border: "1px solid {colors.status-cancelled-border}"
    typography: "{typography.badge-status}"
    rounded: "{rounded.xs}"
    padding: 4px 12px

  form-input-vault:
    backgroundColor: "{colors.canvas}"
    border: "1px solid {colors.hairline}"
    rounded: "{rounded.lg}"
    padding: 10px 16px
    textColor: "{colors.ink-heading}"
    focusBorder: "{colors.primary}"
    focusBackground: "{colors.surface-card}"

  modal-dialog-vault:
    backgroundColor: "{colors.surface-card}"
    rounded: "{rounded.3xl}"
    padding: 24px
    shadow: "0 25px 50px -12px rgba(0, 0, 0, 0.25)"
    border: "1px solid {colors.hairline-soft}"
---

## Overview

**STS (Streetwear to Santri) Admin Vault** adalah sistem antarmuka pengelolaan back-office retail e-commerce yang dibangun dengan perpaduan filosofi **Brutalist-Minimalism** dan estetika **Modern High-Fashion Vault**. Sistem ini dirancang untuk menghadirkan kontrol operasional inventaris, pesanan ritel, manajemen stok masuk/keluar, dan pelaporan keuangan dengan kecepatan eksekusi maksimal, visual yang tegas, dan tanpa distraksi ornamen yang tidak esensial.

Berbeda dengan antarmuka admin generik bertema korporat (biru/ungu cerah), STS Admin dibangun di atas fondasi **monokromatik murni berdaya kontras tinggi**: kanvas abu-abu sangat lembut (`{colors.canvas}` & `{colors.workspace-bg}`), panel sidebar hitam pekat (`{colors.sidebar-bg}` — Zinc-950), dan kartu-kartu data putih bersih (`{colors.surface-card}` — #FFFFFF).

Tipografi ditopang secara penuh oleh font geometrik modern **Figtree**, dipadukan dengan aksen *italic font-black* untuk tajuk utama serta *wide letter-spacing* pada label metadata untuk memancarkan nuansa streetwear editorial kontemporer.

### Karakteristik Desain Utama
- **Monochrome High-Contrast Voltage**: Didominasi oleh palet hitam Zinc-950 (`#09090B`) dan putih bersih (`#FFFFFF`) dengan aksen stroke hairline tipis untuk membedakan level informasi secara tegas.
- **Streetwear Editorial Typography**: Penggunaan tipografi *heavy italic* (`font-black italic tracking-tighter uppercase`) untuk judul dan angka metrik, dipadukan dengan label mikro `text-[9px]` dengan `tracking-[0.2em]`.
- **Vault Architectural Geometry**: Struktur sudut terkontrol (`rounded-2xl` hingga `rounded-3xl` pada kartu data dan `rounded-lg`/`rounded-md` pada tombol serta field input).
- **Semantik Status Fungsional & Tenang**: Badge status pesanan dan stok dirancang berkarakter tipis dan jelas tanpa warna neon mencolok, menjaga fokus mata admin pada throughput data penting.
- **High-Density Operational Layout**: Pengelompokan DataTables, metrik ringkas, grafik fluktuasi 7 hari, dan formulir transaksional yang mengutamakan kecepatan navigasi keyboard dan mouse.

---

## Colors

### Core Monochrome Palette
- **Zinc 950 / Primary Deep** (`{colors.primary}` — #09090B): Warna dominan brand STS. Digunakan pada sidebar navigasi, tajuk display, tombol aksi utama, badge status proses, dan border state aktif.
- **Zinc 900 / Dark Card & Hover** (`{colors.primary-hover}` — #18181B): Digunakan untuk background menu sidebar aktif (`bg-zinc-900`), border pemisah sidebar, dan hover tombol utama.
- **Zinc 800 / Secondary Dark** (`{colors.primary-active}` — #27272A): Warna hover lanjutan dan border elemen gelap sekunder.
- **Zinc 600 / Muted Dark** (`{colors.body}` — #52525B): Header seksi navigasi sidebar dan label data sekunder.
- **Zinc 400 / Subtitle & Hint** (`{colors.muted-soft}` — #A1A1AA): Teks bantuan, breadcrumb sekunder, label tanggal sistem, dan ikon status non-aktif.
- **Zinc 200 / Hairline Border** (`{colors.hairline}` — #E4E4E7): Stroke 1px standar untuk pemisah tabel, border input form, dan pembatas header.
- **Zinc 100 / Hairline Soft** (`{colors.hairline-soft}` — #F4F4F5): Garis pemisah internal pada kartu metrik dan baris DataTables.
- **Zinc 50 / Surface Canvas** (`{colors.canvas}` — #FAFAFA): Background input search bar, container thumbnail foto produk, dan header DataTables.
- **Pure White** (`{colors.surface-card}` — #FFFFFF): Surface utama untuk kartu metrik, tabel data, modal popover, dan dropdown menu.

### Order Status Semantic Palette
Status pesanan menggunakan badge berbingkai halus (*subtle tinted pill/tag*) dengan font tebal miring (`italic font-black text-[10px]`):

| Status Pesanan | Background | Text Color | Border Color | Makna Operasional |
|---|---|---|---|---|
| **Pending** | `{colors.status-pending-bg}` (#F4F4F5) | `{colors.status-pending-text}` (#52525B) | `{colors.status-pending-border}` (#E4E4E7) | Menunggu konfirmasi pembayaran / verifikasi admin |
| **Processed** | `{colors.status-processed-bg}` (#09090B) | `{colors.status-processed-text}` (#FFFFFF) | `{colors.status-processed-border}` (#09090B) | Pesanan sedang disiapkan di warehouse |
| **Shipped** | `{colors.status-shipped-bg}` (#EFF6FF) | `{colors.status-shipped-text}` (#2563EB) | `{colors.status-shipped-border}` (#DBEAFE) | Paket telah diserahkan ke kurir ekspedisi |
| **Completed** | `{colors.status-completed-bg}` (#ECFDF5) | `{colors.status-completed-text}` (#059669) | `{colors.status-completed-border}` (#A7F3D0) | Transaksi sukses & paket diterima pelanggan |
| **Cancelled** | `{colors.status-cancelled-bg}` (#FFF1F2) | `{colors.status-cancelled-text}` (#E11D48) | `{colors.status-cancelled-border}` (#FFE4E6) | Pesanan dibatalkan / bukti transfer tidak valid |

### Inventory & Stock Semantics
- **Stok Kritis (<= 5 SKU)**: Background `{colors.stock-critical-bg}` (#FEF2F2), Teks `{colors.stock-critical-text}` (#DC2626) dengan label `KRITIS: {n}`.
- **Stok Aman / Normal**: Background `{colors.stock-normal-bg}` (#F4F4F5), Teks `{colors.stock-normal-text}` (#52525B).

### System Feedback & Toasts
- **Success Toast** (`{colors.success}` — #059669): Konfirmasi penyimpanan produk, update status pesanan, dan penyesuaian stok.
- **Warning Alert** (`{colors.warning}` — #D97706): Konfirmasi dialog SweetAlert2 sebelum menghapus kategori/produk.
- **Error Toast / Danger** (`{colors.error}` — #DC2626): Notifikasi kegagalan validasi atau stok tidak mencukupi.
- **Realtime Pulse Dot** (`#10B981`): Indikator status transaksi online / sinkronisasi tanggal sistem aktif.

---

## Typography

### Unified Typography System (Figtree)
Sistem administrasi STS menggunakan keluarga font **Figtree** yang bersih, modern, dan sangat terbaca pada kepadatan tabel data:

- **Karakter**: Geometri neo-grotesque yang presisi dengan keterbacaan angka (*tabular data*) yang sangat tinggi.
- **Bobot yang Digunakan**: 400 (Regular), 500 (Medium), 600 (SemiBold), 700 (Bold), 800 (ExtraBold), 900 (Black).
- **CSS Penerapan**: `font-sans` (`font-family: 'Figtree', sans-serif`).

### Skala & Hierarki Tipografi

| Token | Ukuran | Bobot | Line Height | Tracking | Transform / Style | Penggunaan Utama |
|---|---|---|---|---|---|---|
| `{typography.display-xl}` | 32px | 900 (Black) | 1.15 | -0.04em | Uppercase Italic | Judul Halaman Utama ("DASHBOARD", "MANAJEMEN PRODUK") |
| `{typography.display-lg}` | 24px | 900 (Black) | 1.20 | -0.03em | Uppercase Italic | Nomor Order Detail ("#STS-20261002-001"), Heading Modal |
| `{typography.display-md}` | 20px | 900 (Black) | 1.25 | -0.025em| Uppercase Italic | Nilai Metrik Angka Ringkasan & Total Omset |
| `{typography.display-sm}` | 18px | 800 (ExtraBold) | 1.30 | -0.02em| Uppercase | Sub-judul Bagian Laporan, Form Card Titles |
| `{typography.title-lg}` | 16px | 800 (ExtraBold) | 1.35 | -0.01em| Normal | Nama Customer, Judul Card Informasi Pembeli |
| `{typography.title-md}` | 14px | 700 (Bold) | 1.40 | -0.01em| Normal | Nama Artikel Produk pada Tabel Data |
| `{typography.title-sm}` | 13px | 700 (Bold) | 1.40 | 0 | Normal | Label Input Formulir, Sub-metrik |
| `{typography.body-md}` | 14px | 400 (Regular) | 1.60 | 0 | Normal | Deskripsi Produk, Alamat Lengkap Pengiriman |
| `{typography.body-sm}` | 12px | 500 (Medium) | 1.50 | 0 | Normal | Teks Bantuan Input, Deskripsi Singkat Header |
| `{typography.table-header}` | 11px | 900 (Black) | 1.40 | +0.10em | Uppercase | Header Kolom DataTables ("PRODUK", "HARGA", "STOK") |
| `{typography.label-micro}` | 9px | 900 (Black) | 1.30 | +0.20em | Uppercase | Label Atas Metrik ("TOTAL KOLEKSI", "PENDAPATAN") |
| `{typography.nav-section}` | 9px | 800 (ExtraBold) | 1.30 | +0.20em | Uppercase | Pembatas Menu Sidebar ("STOREFRONT", "MANAGEMENT") |
| `{typography.nav-link}` | 11px | 600 (SemiBold) | 1.40 | +0.15em | Uppercase | Tautan Navigasi Sidebar ("CATEGORIES", "ORDERS") |
| `{typography.button}` | 11px | 800 (ExtraBold) | 1.00 | +0.15em | Uppercase | Tombol Aksi ("TAMBAH PRODUK", "UPDATE STATUS") |
| `{typography.badge-status}` | 10px | 900 (Black) | 1.20 | +0.08em | Uppercase Italic | Label Status Pesanan & Status Stok |

---

## Layout

### Struktur Grid & Pembagian Ruang Kerja
STS Admin menerapkan arsitektur layout **Two-Column Dashboard Workspace** yang solid:
1. **Sidebar Navigasi Tetap (Fixed / Sticky 256px)**:
   - Lebar 64 (`w-64`), tinggi layar penuh (`h-screen`), background Zinc-950 (`#09090B`).
   - Menyematkan identitas brand logo di bagian atas, navigasi bertingkat di bagian tengah, dan tombol *Sign Out* di bagian bawah.
2. **Top Header Bar (Sticky 80px)**:
   - Header putih (`h-20 bg-white border-b border-zinc-200`) berisi breadcrumb navigasi dinamis dan profil admin dengan foto avatar.
3. **Workspace Main Canvas (`flex-1 bg-zinc-50/50 p-4 md:p-10`)**:
   - Area konten utama berdaya responsif tinggi dengan container `max-w-7xl` untuk alur detail order dan grid fleksibel untuk modul operasional.

### Grid Metrik & Dashboard Spacing
- **4-Column KPI Grid**: `grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6` untuk ringkasan cepat produk, pendapatan hari ini, antrean pesanan, dan pelanggan.
- **Split Dashboard Area**: `grid grid-cols-1 lg:grid-cols-3 gap-8` (2 Kolom Grafik & Tabel Transaksi Terkini vs 1 Kolom Ringkasan Cepat).
- **12-Column Order Detail Split**: `grid grid-cols-1 lg:grid-cols-12 gap-8` (8 Kolom Rincian Item + 4 Kolom Logistik & Pembayaran).

---

## Elevation & Depth

| Tingkat Elevasi | Perlakuan Visual | Contoh Penerapan |
|---|---|---|
| **Flat Canvas** | Tanpa shadow, background `{colors.workspace-bg}` / `{colors.canvas}` | Background ruang kerja utama admin |
| **Subtle Stroke Card** | 1px border `{colors.hairline}` + `shadow-sm` | Kartu metrik, box form input, panel tabel |
| **Hover Focus Stroke** | Transisi border `border-zinc-100` -> `border-zinc-950` | Interaksi hover pada kartu metrik dashboard |
| **Elevated Header** | `bg-white` sticky + `border-b border-zinc-200` + `z-30` | Topbar admin desktop |
| **Drawer Overlay** | `bg-black/50 backdrop-blur-xs` + `z-40` | Mobile sidebar backdrop |
| **Modal & Dropdown Vault** | `bg-white` + `rounded-3xl` + `shadow-2xl` + `border border-zinc-100` | Dropdown profil admin, modal upload bukti transfer |

---

## Shapes & Geometry

### Hirarki Sudut (Border Radius)

| Token | Nilai | Implementasi Komponen |
|---|---|---|
| `{rounded.xs}` | 4px (`rounded`) | Badge status pesanan, badge stok kritis, indikator logo |
| `{rounded.sm}` | 6px (`rounded-md`) | Dropdown filter items, sub-indikator grafik |
| `{rounded.md}` | 8px (`rounded-lg`) | Tombol aksi utama, field input form, pagination button DataTables |
| `{rounded.lg}` | 12px (`rounded-xl`) | Thumbnail foto produk ritel, box tanggal sistem |
| `{rounded.xl}` | 16px (`rounded-2xl`) | Kartu metrik KPI, box tabel DataTables, form card |
| `{rounded.2xl}` | 20px (`rounded-2xl`) | Container kartu utama detail pesanan |
| `{rounded.3xl}` | 24px (`rounded-3xl`) | Card grafik penjualan 7 hari, modal popover |
| `{rounded.full}` | 9999px (`rounded-full`)| Live realtime dot indicator, avatar profile circle |

---

## Components

### 1. Sidebar Navigasi (`sidebar-container`)
- **Struktur Menu Bertingkat**:
  - **Identitas Brand**: Logo box putih 28x28px dengan huruf "S" miring tebal + teks `STS.` tracking lebar.
  - **Seksi Storefront**: Categories, Products.
  - **Seksi Management**: Stock In, Stock Out, Orders.
  - **Seksi Reports**: Stock Reports, Sales Reports.
  - **Seksi Website**: Edit Website (Site Settings).
  - **Seksi Account**: Admin Profile.
- **Active State**: Background `bg-zinc-900`, teks putih `text-white font-medium`, ikon stroke presisi 1.5.
- **Hover State**: `hover:bg-zinc-900/50 hover:text-zinc-200` dengan transisi halus 200ms.

### 2. Top Header & Profile Dropdown
- **Breadcrumb Navigasi**: Teks `Administration / {Halaman}` dengan format uppercase tracking lebar.
- **Profile Pill**: Avatar kotak bersudut membulat (`rounded-xl border border-zinc-800`), menampilkan foto profil atau inisial nama.
- **Alpine.js Popover Dropdown**: Transisi scale & opacity halus, menampilkan status online, shortcut profil, dan tombol *Keluar* bertinta merah.

### 3. Metric KPI Cards (`metric-stat-card`)
- **Header**: Label kategori metrik `text-[9px] font-black uppercase tracking-[0.2em] text-zinc-400`.
- **Nilai Metrik**: Angka besar `text-3xl font-black text-zinc-950 italic tracking-tight`.
- **Footer Bordered**: Garis hairline bawah tipis berisi keterangan tipe database dan indikator status (contoh: `● Realtime`).
- **Mikro Interaksi**: Border berubah menjadi hitam tegas saat kursor melintas (`hover:border-zinc-950 transition-all duration-300`).

### 4. DataTables & List Views
- **Header Baris**: Huruf kapital tebal dengan latar belakang lembut `bg-zinc-50`.
- **Thumbnail Produk Double-Layer**: Thumbnail 64x64px dengan efek hover transisi foto depan ke foto belakang artikel ritel (`group-hover:opacity-100`).
- **Custom Pagination**: Tombol halaman berbingkai bersih (`border-zinc-200 bg-white`), halaman aktif berwarna hitam pekat (`bg-zinc-950 text-white font-black`).
- **Search Input Vault**: Input pencarian terintegrasi dengan sudut `rounded-xl` dan fokus border hitam.

### 5. Order Management & Quick Status Updater
- **Badge Status Terpadu**: Komponen penanda status 5 fase (Pending -> Processed -> Shipped -> Completed / Cancelled).
- **Fast Status Form**: Dropdown aksi dinamis di pojok kanan atas detail pesanan yang hanya menampilkan status logis berikutnya (misal: dari *pending* langsung ke *processed*).
- **Tabel Rincian Item**: Menampilkan artikel pakaian, ukuran SKU, berat gramasi murni, harga satuan, dan subtotal yang terintegrasi dengan perhitungan ongkos kirim.

### 6. Modal & SweetAlert2 Confirmations
- **Hapus Data Terproteksi**: Menggunakan SweetAlert2 dengan tombol konfirmasi tegas `YA, HAPUS` berwarna merah dan tombol `BATAL` netral.
- **Toastr Notification Hub**: Notifikasi mengambang di pojok kanan atas (`toast-top-right`) dengan progress bar waktu 3 detik.

---

## Do's and Don'ts

### Do
- **Gunakan Tipografi Figtree Konsisten**: Pertahankan font Figtree di seluruh elemen admin. Gunakan aksen *italic font-black* untuk judul halaman dan angka metrik.
- **Terapkan Monokrom Tegas**: Selalu gunakan Zinc-950 (`#09090B`) sebagai warna aksen utama, border aktif, dan background tombol primer.
- **Pelihara Kode Warna Status**: Selalu pasangkan status pesanan dengan token semantik yang telah ditetapkan (*Pending: Gray, Processed: Zinc-950, Shipped: Blue, Completed: Emerald, Cancelled: Rose*).
- **Format Rupiah Standar**: Tuliskan nominal mata uang secara lengkap (`Rp 250.000`) dengan pemisah titik ribuan.
- **Sertakan Satuan Gram**: Tampilkan bobot artikel produk dengan satuan gram (`g`) untuk mendukung akurasi kalkulasi ongkir.

### Don't
- **Jangan Gunakan Warna Primer Neon / Biru Standar**: Hindari warna biru bootstrap atau ungu generik untuk tombol utama admin; STS berkarakter *monochrome vault*.
- **Jangan Buat Sudut Terlalu Tajam Tanpa Radius**: Hindari `rounded-none` pada kartu dan tombol; gunakan `rounded-lg` atau `rounded-2xl` sesuai hierarki.
- **Jangan Hilangkan Konfirmasi Hapus**: Dilarang mengeksekusi penghapusan data tanpa konfirmasi dialog SweetAlert2.
- **Jangan Gunakan Font Serif atau Display Liar**: Hindari memasukkan font seperti Times New Roman, Comic Sans, atau font display dekoratif lainnya.

---

## Responsive Behavior

### Breakpoints & Layout Adaptations

| Breakpoint | Rentang Layar | Adaptasi Tata Letak Admin |
|---|---|---|
| **Mobile** | `< 768px` | Sidebar berpindah ke mode drawer tersembunyi dengan tombol hamburger pada header hitam 64px; tabel data mengaktifkan scroll horizontal (`overflow-x-auto`); grid metrik berubah menjadi 1 kolom. |
| **Tablet** | `768px – 1024px` | Sidebar tetap terbuka (lebar 256px); grid metrik menampilkan 2 kolom; formulir detail pesanan bertumpuk vertikal. |
| **Desktop** | `> 1024px` | Tampilan penuh 4-kolom metrik; 12-kolom split pada detail order; tabel data dan grafik penjualan berdampingan secara proporsional. |

---

## Iteration Guide

Ketika menambahkan modul administrasi baru (misalnya *Voucher Diskon*, *Manajemen Banner*, atau *Customer Blacklist*), ikuti alur standar berikut:

1. **Gunakan Wrapper Layout Standar**: Selalu bungkus view baru dengan `@extends('layouts.admin')` dan `@section('content')`.
2. **Struktur Header Konsisten**:
   ```html
   <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
       <div>
           <h1 class="text-2xl font-black tracking-tighter text-zinc-950 uppercase italic">Nama Modul</h1>
           <p class="text-sm text-zinc-500">Deskripsi fungsi modul secara ringkas.</p>
       </div>
       <a href="..." class="inline-flex items-center px-6 py-3 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-bold uppercase tracking-widest rounded-lg">
           + Tambah Data
       </a>
   </div>
   ```
3. **Penerapan DataTables**: Tambahkan class `datatable` pada tag `<table>` agar script otomatis mengatur pagination, pencarian, dan styling vault.
4. **Proteksi Form & SweetAlert2**: Lengkapi aksi hapus dengan form DELETE berkonfirmasi modal SweetAlert2.

---

## Known Gaps & Future Work

- **Export Laporan (Excel & PDF)**: Integrasi generator ekspor spreadsheet otomatis untuk laporan penjualan dan riwayat stok.
- **Batch Action Checkbox**: Fitur seleksi multi-baris pada tabel produk dan pesanan untuk update status massal.
- **Live WebSocket Orders Notification**: Denting audio dan popup realtime saat ada pesanan baru masuk tanpa perlu reload halaman.
- **Dark Mode Switcher Workspace**: Dukungan tema gelap penuh untuk canvas ruang kerja admin.
