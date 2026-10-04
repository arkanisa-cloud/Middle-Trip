# UI Kit: Dropdown & Combobox — MiddleTrip Design System

Dokumen ini mendokumentasikan spesifikasi desain, anatomi, interaksi, dan implementasi kode untuk **Dropdown** dan **Combobox (Searchable Dropdown)** pada MiddleTrip. Panduan ini memastikan konsistensi visual dan fungsional di seluruh halaman (seperti Hero Search Bar, Filter Katalog, dan formulir lainnya di masa mendatang).

---

## 1. Filosofi & Prinsip Desain

Native `<select>` HTML memiliki keterbatasan visual: gaya bergantung pada OS masing-masing, tidak mendukung metadata visual (seperti badge ketinggian atau indikator warna), dan tidak mendukung animasi transisi modern.

UI Kit ini menggabungkan:
- **Capsule / Pill Geometry**: Trigger berbentuk kapsul yang nyaman disentuh (`rounded-full`) dengan padding proporsional.
- **Card Elevation**: Menu dropdown melayang (`shadow-xl`) dengan sudut membulat modern (`rounded-2xl`) dan border tipis halus (`border border-hairline`).
- **Category Header**: Header kategori uppercase kecil (`text-[10px] tracking-wider text-muted-soft`) untuk membedakan konteks pilihan.
- **Metadata Alignment**: Opsi baris memanfaatkan ruang horizontal (`flex items-center justify-between`) untuk menyajikan informasi sekunder (MDPL, nama gunung induk, atau colored difficulty dot).
- **Smooth Micro-Interaction**: Ikon chevron berputar halus 180 derajat (`transition-transform duration-200`) saat dibuka dan ditutup.

---

## 2. Token Desain (Design Tokens)

| Token | Nilai / Tailwind Class | Kegunaan |
|---|---|---|
| **Border** | `border border-hairline` (`#E5E7EB`) | Garis tepi kontainer dropdown menu |
| **Radius Menu** | `rounded-2xl` (16px) | Sudut melengkung elevated menu |
| **Radius Trigger** | `rounded-full` (9999px) | Bentuk pil trigger dropdown |
| **Shadow** | `shadow-xl` | Efek melayang menu dropdown di atas konten |
| **Header Text** | `text-[10px] uppercase font-bold text-muted-soft tracking-wider` | Label kategori di bagian paling atas menu |
| **Option Text** | `text-xs md:text-[13px] font-medium text-body-strong` | Label utama pilihan |
| **Option Subtitle** | `text-[10px] text-muted font-normal` | Keterangan sekunder (misal MDPL atau nama gunung) |
| **Hover State** | `hover:bg-gray-50` | Background saat baris di-hover kursor |
| **Chevron Animation** | `transition-transform duration-200` + `.rotate-180` | Animasi rotasi panah indikator |

---

## 3. Varian Komponen

MiddleTrip memiliki 3 varian utama yang saling selaras:

### Varian 1: Searchable Combobox (Pencarian Teks + Dropdown)
Digunakan saat pilihan memiliki banyak item (contoh: Pilihan Gunung). Pengguna dapat mengetik untuk memfilter secara *real-time* atau langsung memilih dari daftar.

**Anatomi:**
1. **Leading Icon**: Ikon peta / lokasi (`stroke-width="1.8"`).
2. **Text Input**: `<input type="text" autocomplete="off">` transparan tanpa border default.
3. **Chevron Indicator**: Panah ke bawah yang berputar saat dropdown terbuka.
4. **Filtered Options**: Daftar baris dengan filter live JavaScript.

```html
<!-- Searchable Combobox Template -->
<div class="relative w-full md:w-1/3">
    <div id="mountain-select-trigger"
        class="flex items-center gap-3 w-full px-4 py-2 hover:bg-gray-50 rounded-full cursor-pointer transition select-none">
        <svg class="w-5 h-5 text-muted-soft shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <div class="flex-1 text-left min-w-0">
            <input type="text" id="mountain-search-input" name="gunung"
                placeholder="Pilih Gunung" autocomplete="off"
                class="w-full bg-transparent text-[13px] font-semibold text-body-strong placeholder-muted focus:outline-none cursor-pointer" />
        </div>
        <svg id="mountain-chevron" class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>

    <!-- Elevated Menu -->
    <div id="mountain-dropdown-list"
        class="hidden absolute left-0 right-0 md:w-72 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 max-h-56 overflow-y-auto text-xs">
        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider">
            Pilih Gunung
        </div>
        <button type="button" onclick="selectMountain('')"
            class="mountain-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
            <span>Semua Gunung</span>
        </button>
        <button type="button" onclick="selectMountain('Mt. Merbabu')"
            class="mountain-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
            <span>Mt. Merbabu</span>
            <span class="text-[10px] text-muted font-normal">3.142 MDPL</span>
        </button>
    </div>
</div>
```

---

### Varian 2: Standard Single-Select Dropdown (Pilih Opsi)
Digunakan untuk pilihan rute, jalur, atau kategori (contoh: Pilih Jalur). Memanfaatkan `<input type="hidden">` agar kompatibel dengan submit formulir standar (GET/POST).

```html
<!-- Standard Dropdown Template -->
<div class="relative w-full md:w-1/3">
    <div id="jalur-select-trigger"
        class="flex items-center gap-3 w-full px-4 py-2 hover:bg-gray-50 rounded-full cursor-pointer transition select-none">
        <svg class="w-5 h-5 text-muted-soft shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
        </svg>
        <div class="flex-1 text-left min-w-0">
            <span id="jalur-display-label" class="text-[13px] font-semibold text-body-strong block truncate">Pilih Jalur</span>
            <input type="hidden" name="jalur" id="jalur-hidden-input" value="" />
        </div>
        <svg id="jalur-chevron" class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>

    <!-- Elevated Menu -->
    <div id="jalur-dropdown-list"
        class="hidden absolute left-0 right-0 md:w-72 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 max-h-56 overflow-y-auto text-xs">
        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider">
            Pilih Jalur
        </div>
        <button type="button" onclick="selectJalur('', 'Pilih Jalur')"
            class="jalur-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
            <span>Semua Jalur</span>
        </button>
        <button type="button" onclick="selectJalur('Selo', 'Jalur Selo')"
            class="jalur-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
            <span>Jalur Selo</span>
            <span class="text-[10px] text-muted font-normal">Merbabu</span>
        </button>
    </div>
</div>
```

---

### Varian 3: Status / Grade Classification Dropdown (Dengan Indikator Dot)
Digunakan untuk memilih tingkat kesulitan pendakian (Grade A, B, C) dengan dot warna yang serasi dengan identitas MiddleTrip:
- **Grade A (Pemula)**: Teks hijau tua (`text-grade-a-text`), dot hijau emerald (`bg-grade-a-dot`).
- **Grade B (Menengah)**: Teks oranye tua (`text-grade-b-text`), dot amber (`bg-grade-b-dot`).
- **Grade C (Ahli)**: Teks merah tua (`text-grade-c-text`), dot rose (`bg-grade-c-dot`).

```html
<!-- Grade Dropdown Template -->
<div class="relative w-full md:w-1/3">
    <div id="grade-hero-trigger"
        class="flex items-center gap-3 w-full px-4 py-2 hover:bg-gray-50 rounded-full cursor-pointer transition select-none">
        <svg class="w-5 h-5 text-muted-soft shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <div class="flex-1 text-left min-w-0">
            <span id="grade-hero-display-label" class="text-[13px] font-semibold text-body-strong block truncate">Semua Level</span>
            <input type="hidden" name="grade" id="grade-hero-hidden-input" value="" />
        </div>
        <svg id="grade-hero-chevron" class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>

    <!-- Elevated Menu -->
    <div id="grade-hero-dropdown-list"
        class="hidden absolute left-0 right-0 md:w-72 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 max-h-56 overflow-y-auto text-xs">
        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider">
            Tingkat Kesulitan
        </div>
        <button type="button" onclick="selectHeroGrade('', 'Semua Level')"
            class="grade-hero-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
            <span>Semua Level</span>
        </button>
        <button type="button" onclick="selectHeroGrade('Grade A', 'Grade A – Pemula')"
            class="grade-hero-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
            <span class="text-grade-a-text">Grade A – Pemula</span>
            <span class="w-2 h-2 rounded-full bg-grade-a-dot"></span>
        </button>
        <button type="button" onclick="selectHeroGrade('Grade B', 'Grade B – Menengah')"
            class="grade-hero-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
            <span class="text-grade-b-text">Grade B – Menengah</span>
            <span class="w-2 h-2 rounded-full bg-grade-b-dot"></span>
        </button>
        <button type="button" onclick="selectHeroGrade('Grade C', 'Grade C – Ahli')"
            class="grade-hero-option w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
            <span class="text-grade-c-text">Grade C – Ahli</span>
            <span class="w-2 h-2 rounded-full bg-grade-c-dot"></span>
        </button>
    </div>
</div>
```

---

### Varian 4: Compact Action Button Dropdown (Filter Toolbar Katalog)
Digunakan pada toolbar filter yang memiliki batasan ruang (contoh pada `katalog.blade.php`). Menggunakan trigger button padat `bg-gray-200/60` dengan ukuran teks `text-xs`.

```html
<!-- Compact Toolbar Dropdown -->
<div class="relative">
    <button id="grade-dropdown-btn" type="button" onclick="toggleGradeMenu()"
        class="bg-gray-200/60 hover:bg-gray-200 text-body-strong text-xs font-semibold px-4 py-2 rounded-full flex items-center gap-2 transition cursor-pointer">
        <span id="grade-selected-label">Grade</span>
        <svg id="grade-chevron" class="w-3.5 h-3.5 text-muted transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    <div id="grade-menu"
        class="hidden absolute right-0 mt-2 w-52 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-30 text-xs">
        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider">
            Pilih Grade
        </div>
        <!-- Opsi sama seperti Varian 3 -->
    </div>
</div>
```

---

## 4. Standar State & Interaksi JavaScript

Untuk menjaga UX yang konsisten, setiap implementasi dropdown/combobox harus mematuhi 4 aturan interaksi:

1. **Mutual Exclusivity**: Ketika satu dropdown dibuka, semua dropdown lain yang sedang aktif harus otomatis ditutup.
2. **Outside Click Dismissal**: Mengklik area di luar dropdown dan trigger harus langsung menutup dropdown dan mengembalikan rotasi chevron ke 0 derajat.
3. **Smooth Chevron Rotation**: Tambahkan class `rotate-180` pada SVG chevron saat terbuka, dan hapus saat tertutup.
4. **Synchronized Hidden Input**: Selalu update `input[type="hidden"]` saat opsi diklik agar form tetap terkirim secara native.

### Contoh Pola Interaksi Standar (Script)

```javascript
// Fungsi utilitas buka/tutup
function openDropdown(menuEl, chevronEl) {
    closeAllDropdowns(); // Tutup dropdown lain
    menuEl.classList.remove('hidden');
    chevronEl?.classList.add('rotate-180');
}

function closeDropdown(menuEl, chevronEl) {
    menuEl.classList.add('hidden');
    chevronEl?.classList.remove('rotate-180');
}

// Event listener klik di luar komponen
document.addEventListener('click', (e) => {
    dropdowns.forEach(({ trigger, menu, chevron }) => {
        if (!trigger.contains(e.target) && !menu.contains(e.target)) {
            closeDropdown(menu, chevron);
        }
    });
});
```

---

## 5. Checklist Penggunaan untuk Fitur Baru

Sebelum menambahkan dropdown atau combobox baru di halaman MiddleTrip, pastikan:
- [ ] Menggunakan `rounded-2xl`, `border-hairline`, dan `shadow-xl` pada kontainer menu.
- [ ] Memiliki header kategori huruf kapital dengan `text-[10px] tracking-wider text-muted-soft`.
- [ ] Menggunakan chevron SVG dengan `transition-transform duration-200` dan class `rotate-180`.
- [ ] Menggunakan `select-none` dan `cursor-pointer` pada trigger.
- [ ] Opsi baris memiliki `hover:bg-gray-50` dan metadata di sebelah kanan jika ada info tambahan.
- [ ] Menyediakan `<input type="hidden">` dengan `name` yang sesuai untuk keperluan submission form.
- [ ] Menangani dismiss klik luar (click-outside).
