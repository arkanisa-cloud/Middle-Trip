@extends('layouts.admin')

@section('title', 'Tambah Gunung')
@section('header_title', 'Tambah Destinasi Gunung Baru')
@section('header_subtitle', 'Lengkapi informasi umum, spesifikasi rute, matriks harga, profil elevasi, dan fasilitas')

@section('content')
    <div x-data="mountainCreateForm()" class="max-w-5xl mx-auto space-y-6">

        <!-- Page Header Banner -->
        <x-admin.page-header 
            badge="Master Data" 
            title="Tambah Destinasi Gunung Baru" 
            subtitle="Lengkapi informasi umum, spesifikasi rute, matriks harga dinamis, profil elevasi, dan fasilitas ekspedisi."
            :backUrl="route('admin.mountains.index')"
            backLabel="Kembali ke Daftar Gunung"
        />

        <form method="POST" action="{{ route('admin.mountains.store') }}" enctype="multipart/form-data" novalidate
            class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs space-y-1">
                    <div class="flex items-center gap-2 font-bold text-sm text-rose-900">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Terdapat Kesalahan Validasi Form:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 pl-1 text-[11px] text-rose-700">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Section 1: Informasi Umum Gunung -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 shadow-xs space-y-6">
                <div class="border-b border-hairline pb-4">
                    <h3 class="text-base font-extrabold text-ink-heading">1. Informasi Umum Destinasi</h3>
                    <p class="text-xs text-muted">Data pokok gunung yang akan ditampilkan pada landing page dan katalog</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Nama Gunung <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Contoh: Mt. Merbabu"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                    </div>

                    <!-- Elevation -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Ketinggian (MDPL) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="elevation" value="{{ old('elevation') }}" required
                            placeholder="Contoh: 3142"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                    </div>

                    <!-- Province -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Provinsi / Wilayah <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="province" value="{{ old('province') }}" required
                            placeholder="Contoh: Jawa Tengah"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                    </div>

                    <!-- Grade Selection -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Tingkat Kesulitan Induk (Grade) <span class="text-rose-500">*</span>
                        </label>
                        <select name="grade" required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs font-semibold">
                            @foreach ($grades as $g)
                                <option value="{{ $g->value }}"
                                    {{ old('grade', 'Grade A') === $g->value ? 'selected' : '' }}>
                                    {{ $g->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cover Image Upload -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Upload Foto Sampul (Cover Image) <span class="text-rose-500">*</span>
                        </label>
                        <div x-data="{
                            preview: '{{ old('cover_image') }}',
                            handleFileSelect(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    this.preview = URL.createObjectURL(file);
                                }
                            }
                        }" class="space-y-3">
                            <div class="flex items-center gap-4">
                                <template x-if="preview">
                                    <div
                                        class="relative w-24 h-20 rounded-2xl overflow-hidden border border-hairline shadow-xs shrink-0">
                                        <img :src="preview" alt="Preview Foto Sampul"
                                            class="w-full h-full object-cover">
                                    </div>
                                </template>
                                <div class="flex-1">
                                    <input type="file" name="cover_image_file" @change="handleFileSelect"
                                        accept="image/*" required
                                        class="w-full text-xs text-muted file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-subtle file:text-primary hover:file:bg-primary hover:file:text-white file:transition-colors file:cursor-pointer border border-hairline rounded-xl bg-canvas p-1.5 focus:border-primary shadow-xs">
                                    <p class="text-[11px] text-muted mt-1.5">Format file yang didukung: JPG, PNG, WEBP
                                        (Ukuran file maksimal 5MB)</p>
                                </div>
                            </div>
                            <input type="hidden" name="cover_image" :value="preview">
                        </div>
                    </div>

                    <!-- Gallery Photos Upload -->
                    <div class="md:col-span-2 border-t border-hairline pt-4" x-data="{
                        files: [],
                        handleFiles(e) {
                            const selectedFiles = Array.from(e.target.files);
                            if (!selectedFiles.length) return;
                    
                            selectedFiles.forEach((f) => {
                                this.files.push({
                                    id: Date.now() + Math.random(),
                                    file: f,
                                    preview: URL.createObjectURL(f),
                                    caption: ''
                                });
                            });
                    
                            if (window.DataTransfer && this.$refs.fileInput) {
                                const dt = new DataTransfer();
                                this.files.forEach(item => dt.items.add(item.file));
                                this.$refs.fileInput.files = dt.files;
                            }
                        },
                        removeFile(index) {
                            this.files.splice(index, 1);
                            if (window.DataTransfer && this.$refs.fileInput) {
                                const dt = new DataTransfer();
                                this.files.forEach(item => dt.items.add(item.file));
                                this.$refs.fileInput.files = dt.files;
                            }
                        },
                        clearAllFiles() {
                            this.files = [];
                            if (this.$refs.fileInput) {
                                this.$refs.fileInput.value = '';
                            }
                        }
                    }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                            <div>
                                <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider">
                                    Galeri Foto Ekspedisi (Opsional, Bento Grid & Lightbox)
                                </label>
                                <p class="text-[11px] text-muted">Upload hingga 10 foto pendukung (pos pendakian, camp area,
                                    sabana, sunrise/sunset). Foto-foto ini akan mengisi 4 kartu galeri dan modal lightbox
                                    detail ekspedisi.</p>
                            </div>
                            <template x-if="files.length > 0">
                                <button type="button" @click="clearAllFiles()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold hover:bg-rose-600 hover:text-white transition-colors self-start sm:self-auto shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Hapus Semua Foto (<span x-text="files.length"></span>)</span>
                                </button>
                            </template>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center gap-4">
                                <input type="file" name="gallery_files[]" x-ref="fileInput" @change="handleFiles"
                                    accept="image/*" multiple
                                    class="w-full text-xs text-muted file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-subtle file:text-primary hover:file:bg-primary hover:file:text-white file:transition-colors file:cursor-pointer border border-hairline rounded-xl bg-canvas p-1.5 focus:border-primary shadow-xs">
                            </div>

                            <!-- Previews Grid -->
                            <template x-if="files.length > 0">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 pt-2">
                                    <template x-for="(item, index) in files" :key="index">
                                        <div
                                            class="relative bg-surface-card rounded-2xl border border-hairline overflow-hidden p-2.5 shadow-xs flex flex-col justify-between">
                                            <div class="aspect-4/3 rounded-xl overflow-hidden mb-2 bg-gray-100 relative">
                                                <img :src="item.preview" class="w-full h-full object-cover">
                                                <button type="button" @click="removeFile(index)" title="Hapus Foto"
                                                    class="absolute top-2 right-2 p-1.5 bg-rose-600 text-white rounded-lg hover:bg-rose-700 shadow-md transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <input type="text" name="gallery_file_captions[]" x-model="item.caption"
                                                placeholder="Keterangan foto..."
                                                class="w-full text-[11px] rounded-lg border border-hairline bg-canvas p-1.5 text-ink focus:border-primary shadow-2xs mb-2">
                                            <button type="button" @click="removeFile(index)"
                                                class="w-full py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white border border-rose-200 text-[10px] font-bold transition-colors flex items-center justify-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Hapus Foto</span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Short Editorial Description -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Deskripsi Singkat (Ringkasan Katalog & Kartu Gunung)
                        </label>
                        <textarea name="description" rows="2"
                            placeholder="Tuliskan ulasan ringkas (1-2 kalimat) untuk kartu pencarian..."
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Konfigurasi Tarif & Aturan Price Lock -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 shadow-xs space-y-6">
                <div class="border-b border-hairline pb-4">
                    <h3 class="text-base font-extrabold text-ink-heading">2. Konfigurasi Booking Fee & Price Lock</h3>
                    <p class="text-xs text-muted">Pengaturan batas DP awal dan hari penguncian harga sebelum keberangkatan
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- Base Price (Camping Open) -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Harga Camping Open (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="base_price" value="{{ old('base_price', 500000) }}" required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Harga dasar open trip paket camping</span>
                    </div>

                    <!-- Private Price (Camping Private) -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Harga Camping Private (Rp)
                        </label>
                        <input type="number" name="price_private" value="{{ old('price_private', 1200000) }}"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Harga dasar private trip paket camping</span>
                    </div>

                    <!-- Price Tektok (Open Trip) -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Harga Tek-tok Open (Rp)
                        </label>
                        <input type="number" name="price_tektok" value="{{ old('price_tektok', 400000) }}"
                            placeholder="Contoh: 400000"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Harga dasar open trip paket 1 hari</span>
                    </div>

                    <!-- Price Tektok Private -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Harga Tek-tok Private (Rp)
                        </label>
                        <input type="number" name="price_private_tektok"
                            value="{{ old('price_private_tektok', 850000) }}" placeholder="Contoh: 850000"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Harga dasar private trip paket 1 hari</span>
                    </div>

                    <!-- Booking Fee per Pax -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Booking Fee DP / Pax (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="booking_fee_per_pax"
                            value="{{ old('booking_fee_per_pax', 150000) }}" required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Uang muka awal untuk kunci kuota</span>
                    </div>

                    <!-- Price Lock Days -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Price Lock (H-X Hari) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="price_lock_days_before_departure"
                            value="{{ old('price_lock_days_before_departure', 3) }}" min="1" max="30"
                            required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Hari sebelum berangkat harga dikunci</span>
                    </div>
                </div>

                <!-- Toggles -->
                <div class="space-y-3 pt-4 border-t border-hairline" x-data="{ isFeatured: {{ old('is_featured') ? 'true' : 'false' }} }">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-ink-heading">
                            <input type="checkbox" name="has_open_trip" value="1" checked
                                class="rounded border-hairline text-primary focus:ring-primary">
                            <span>Open Trip</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-ink-heading">
                            <input type="checkbox" name="has_private_trip" value="1" checked
                                class="rounded border-hairline text-primary focus:ring-primary">
                            <span>Private Trip</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-ink-heading">
                            <input type="checkbox" name="is_featured" value="1" x-model="isFeatured"
                                class="rounded border-hairline text-primary focus:ring-primary">
                            <span>Featured di Home</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-ink-heading">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="rounded border-hairline text-primary focus:ring-primary">
                            <span>Aktif Publikasi</span>
                        </label>
                    </div>

                    <!-- Dropdown Posisi Slot Featured Bento di Home -->
                    <div x-show="isFeatured" x-cloak
                        class="p-3.5 bg-amber-50/70 border border-amber-200/80 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="font-bold text-amber-950 block">Posisi Slot Bento Grid Home:</span>
                            <span class="text-[11px] text-amber-800">Tentukan di slot mana kartu gunung ini akan dipajang
                                pada halaman depan</span>
                        </div>
                        <div class="shrink-0">
                            <select name="featured_order"
                                class="bg-white border border-amber-300 rounded-xl px-3 py-1.5 text-xs font-bold text-ink-heading focus:outline-none focus:ring-1 focus:ring-primary">
                                <option value="1" {{ old('featured_order', 1) == 1 ? 'selected' : '' }}>Slot 1: Hero
                                    Utama (Kiri Lebar - Span 7)</option>
                                <option value="2" {{ old('featured_order') == 2 ? 'selected' : '' }}>Slot 2: Kartu
                                    Kanan Atas (Span 5)</option>
                                <option value="3" {{ old('featured_order') == 3 ? 'selected' : '' }}>Slot 3: Kartu
                                    Kanan Bawah (Span 5)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Jalur Pendakian, Profil Elevasi & Itinerary (Per Rute) -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 shadow-xs space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-hairline pb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-ink-heading">3. Jalur Pendakian Resmi, Profil Elevasi &
                            Itinerary</h3>
                        <p class="text-xs text-muted">Setiap jalur memiliki pos elevasi (MDPL), mitigasi air/angin, dan
                            jadwal timeline 2D1N mandiri</p>
                    </div>
                    <button type="button" @click="addRoute()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-primary-subtle text-primary text-xs font-bold hover:bg-primary hover:text-white transition-colors shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Jalur Baru</span>
                    </button>
                </div>

                <!-- Route Cards Accordion List -->
                <div class="space-y-4">
                    <template x-if="routes.length === 0">
                        <div class="p-8 text-center border-2 border-dashed border-hairline rounded-3xl bg-canvas">
                            <p class="text-xs text-muted mb-3">Belum ada jalur pendakian yang ditambahkan untuk destinasi ini.</p>
                            <button type="button" @click="addRoute()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-primary-subtle text-primary text-xs font-bold hover:bg-primary hover:text-white transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Jalur Baru</span>
                            </button>
                        </div>
                    </template>
                    <template x-for="(r, index) in routes" :key="index">
                        <div class="rounded-3xl border border-hairline bg-canvas overflow-hidden shadow-xs transition-all">
                            <!-- Route Header Summary Bar -->
                            <div
                                class="p-4 md:p-5 bg-surface-card border-b border-hairline flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="w-7 h-7 rounded-xl bg-primary/10 text-primary font-bold text-xs flex items-center justify-center shrink-0">
                                        #<span x-text="index + 1"></span>
                                    </span>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-extrabold text-ink-heading"
                                                x-text="r.name ? r.name : 'Jalur Baru'"></h4>
                                            <template x-if="r.is_primary">
                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                                    Jalur Utama
                                                </span>
                                            </template>
                                        </div>
                                        <p class="text-[11px] text-muted">
                                            <span x-text="r.distance_km ? r.distance_km + ' km' : '— km'"></span> •
                                            <span x-text="r.duration_hours ? r.duration_hours : '— Jam'"></span> •
                                            <span x-text="r.grade || 'Grade A'"></span> •
                                            <span
                                                x-text="r.checkpoints ? r.checkpoints.length + ' Pos Elevasi' : '0 Pos'"></span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-end md:self-auto">
                                    <button type="button" @click="toggleRoute(index)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-hairline bg-canvas text-xs font-bold text-ink hover:text-primary transition-colors">
                                        <span x-text="r.isOpen ? 'Tutup Detail' : 'Kelola Elevasi & Itinerary'"></span>
                                        <svg class="w-3.5 h-3.5 transition-transform duration-200"
                                            :class="r.isOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <button type="button" @click="removeRoute(index)"
                                        class="p-2 text-muted hover:text-rose-600 rounded-xl hover:bg-rose-50 transition-colors"
                                        title="Hapus Jalur">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Route Specifications Basic Inputs -->
                            <div
                                class="p-4 md:p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 border-b border-hairline bg-canvas">
                                <!-- Route Name -->
                                <div class="lg:col-span-2">
                                    <label class="block text-[11px] font-bold text-muted mb-1">Nama Jalur (Via) <span
                                            class="text-rose-500">*</span></label>
                                    <input type="text" :name="`routes[${index}][name]`" x-model="r.name" required
                                        placeholder="Contoh: Via Selo"
                                        class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2.5 text-ink focus:border-primary font-semibold">
                                </div>

                                <!-- Route Grade -->
                                <div>
                                    <label class="block text-[11px] font-bold text-muted mb-1">Grade Kesulitan</label>
                                    <select :name="`routes[${index}][grade]`" x-model="r.grade"
                                        class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2.5 text-ink focus:border-primary font-semibold">
                                        <option value="Grade A">Grade A – Pemula</option>
                                        <option value="Grade B">Grade B – Menengah</option>
                                        <option value="Grade C">Grade C – Ahli</option>
                                    </select>
                                </div>

                                <!-- Distance KM -->
                                <div>
                                    <label class="block text-[11px] font-bold text-muted mb-1">Jarak (KM)</label>
                                    <input type="number" step="0.1" :name="`routes[${index}][distance_km]`"
                                        x-model="r.distance_km" placeholder="9.5"
                                        class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2.5 text-ink focus:border-primary">
                                </div>

                                <!-- Duration Hours -->
                                <div>
                                    <label class="block text-[11px] font-bold text-muted mb-1">Durasi</label>
                                    <input type="text" :name="`routes[${index}][duration_hours]`"
                                        x-model="r.duration_hours" placeholder="6-8 Jam"
                                        class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2.5 text-ink focus:border-primary">
                                </div>

                                <!-- Primary Toggle -->
                                <div class="sm:col-span-2 lg:col-span-5 pt-1">
                                    <label
                                        class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-ink-heading">
                                        <input type="checkbox" :name="`routes[${index}][is_primary]`" value="1"
                                            x-model="r.is_primary"
                                            @change="if (r.is_primary) routes.forEach((other, i) => { if (i !== index) other.is_primary = false; })"
                                            class="rounded border-hairline text-primary focus:ring-primary">
                                        <span>Jadikan sebagai Jalur Utama (Rekomendasi)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Expandable Sub-Section: Elevasi & Itinerary -->
                            <div x-show="r.isOpen" x-collapse
                                class="p-5 md:p-6 bg-surface-card/60 space-y-6 border-t border-hairline">
                                <!-- Sub Tabs Navigation -->
                                <div class="flex items-center gap-2 border-b border-hairline pb-3">
                                    <button type="button" @click="r.activeSubTab = 'elevation'"
                                        :class="r.activeSubTab === 'elevation' ? 'bg-primary text-white shadow-xs' :
                                            'bg-canvas text-muted hover:text-ink border border-hairline'"
                                        class="px-4 py-1.5 rounded-full text-xs font-bold transition-all flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                        <span>Profil Elevasi & Pos (<span x-text="r.checkpoints.length"></span> Pos)</span>
                                    </button>
                                    <button type="button" @click="r.activeSubTab = 'itinerary'"
                                        :class="r.activeSubTab === 'itinerary' ? 'bg-primary text-white shadow-xs' :
                                            'bg-canvas text-muted hover:text-ink border border-hairline'"
                                        class="px-4 py-1.5 rounded-full text-xs font-bold transition-all flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Itinerary (<span x-text="getRouteDurationLabel(r)"></span>)</span>
                                    </button>
                                </div>

                                <!-- Sub-Tab 1: Checkpoints Elevasi -->
                                <div x-show="r.activeSubTab === 'elevation'" class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h5 class="text-xs font-extrabold text-ink-heading uppercase tracking-wider">
                                                Urutan Pos Elevasi Jalur Ini</h5>
                                            <p class="text-[11px] text-muted">Input pos dari awal Basecamp hingga Puncak
                                                tertinggi untuk membangun grafik kontur SVG otomatis</p>
                                        </div>
                                        <button type="button" @click="addRouteCheckpoint(index)"
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold hover:bg-emerald-600 hover:text-white transition-colors">
                                            + Tambah Pos
                                        </button>
                                    </div>

                                    <div class="space-y-2.5">
                                        <template x-for="(cp, cpIndex) in r.checkpoints" :key="cpIndex">
                                            <div
                                                class="p-3 rounded-2xl bg-canvas border border-hairline grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                                                <div class="sm:col-span-1 text-center font-bold text-xs text-primary">
                                                    #<span x-text="cpIndex + 1"></span>
                                                </div>
                                                <div class="sm:col-span-6">
                                                    <label class="block text-[10px] font-bold text-muted mb-1">Nama
                                                        Checkpoint</label>
                                                    <input type="text"
                                                        :name="`routes[${index}][checkpoints][${cpIndex}][name]`"
                                                        x-model="cp.name"
                                                        placeholder="Contoh: Basecamp / Pos 1 / Sabana / Puncak"
                                                        class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary">
                                                </div>
                                                <div class="sm:col-span-4">
                                                    <label class="block text-[10px] font-bold text-muted mb-1">Ketinggian
                                                        (MDPL)</label>
                                                    <input type="number"
                                                        :name="`routes[${index}][checkpoints][${cpIndex}][elevation]`"
                                                        x-model="cp.elevation" placeholder="Contoh: 1800"
                                                        class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary font-semibold">
                                                </div>
                                                <div class="sm:col-span-1 text-right pt-2 sm:pt-0">
                                                    <button type="button" @click="removeRouteCheckpoint(index, cpIndex)"
                                                        class="p-2 text-muted hover:text-rose-600 rounded-lg transition-colors"
                                                        title="Hapus Pos">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Route Elevation Notes -->
                                    <div class="pt-4 border-t border-hairline space-y-3">
                                        <h6 class="text-[11px] font-bold text-ink-heading uppercase tracking-wider">Catatan
                                            Karakteristik & Mitigasi Jalur Ini</h6>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-blue-700 mb-1 flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Sumber Air
                                                    Terakhir
                                                </label>
                                                <input type="text" :name="`routes[${index}][water_note]`"
                                                    x-model="r.water_note"
                                                    class="w-full text-xs rounded-xl border border-hairline bg-canvas p-2 text-ink focus:border-primary">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-amber-700 mb-1 flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Zona Terpaan
                                                    Angin
                                                </label>
                                                <input type="text" :name="`routes[${index}][wind_note]`"
                                                    x-model="r.wind_note"
                                                    class="w-full text-xs rounded-xl border border-hairline bg-canvas p-2 text-ink focus:border-primary">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[10px] font-bold text-emerald-700 mb-1 flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sinyal
                                                    Seluler
                                                </label>
                                                <input type="text" :name="`routes[${index}][signal_note]`"
                                                    x-model="r.signal_note"
                                                    class="w-full text-xs rounded-xl border border-hairline bg-canvas p-2 text-ink focus:border-primary">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sub-Tab 2: Itinerary Timeline (Camping vs Tek-tok) -->
                                <div x-show="r.activeSubTab === 'itinerary'" class="space-y-4">
                                    <!-- Mode Selector Toggle -->
                                    <div
                                        class="flex items-center gap-2 p-1 rounded-xl bg-canvas border border-hairline w-fit">
                                        <button type="button" @click="r.itinerary_type = 'camping'"
                                            :class="(r.itinerary_type || 'camping') === 'camping' ?
                                                'bg-primary text-white shadow-xs' : 'text-muted hover:text-ink'"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all">
                                            Itinerary Camping
                                        </button>
                                        <button type="button" @click="r.itinerary_type = 'tektok'"
                                            :class="r.itinerary_type === 'tektok' ? 'bg-primary text-white shadow-xs' :
                                                'text-muted hover:text-ink'"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all">
                                            Itinerary Tek-tok
                                        </button>
                                    </div>

                                    <!-- View 1: Camping (Multi-Day) -->
                                    <div x-show="(r.itinerary_type || 'camping') === 'camping'" class="space-y-4">
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-hairline pb-3">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h5
                                                        class="text-xs font-extrabold text-ink-heading uppercase tracking-wider">
                                                        Timeline Rencana Pendakian Camping</h5>
                                                    <span
                                                        class="px-2.5 py-0.5 rounded-full bg-primary-subtle text-primary font-bold text-[11px]"
                                                        x-text="getRouteDurationLabel(r)"></span>
                                                </div>
                                                <p class="text-[11px] text-muted">Atur rencana hari pendakian camping
                                                    bermalam di tenda. Format: <code
                                                        class="font-mono bg-canvas px-1 py-0.5 rounded">08:00 -
                                                        Registrasi</code></p>
                                            </div>
                                            <button type="button" @click="addItineraryDay(index)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-600 hover:text-white transition-colors self-start sm:self-auto shrink-0">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                <span>+ Tambah Hari (Day)</span>
                                            </button>
                                        </div>

                                        <!-- Multi-Day Cards Grid -->
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                            <template x-for="(dayItem, dIndex) in r.itinerary_days"
                                                :key="dIndex">
                                                <div
                                                    class="p-4 rounded-2xl bg-canvas border border-hairline space-y-3 relative group">
                                                    <div
                                                        class="flex items-center justify-between pb-2 border-b border-hairline">
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="px-2.5 py-0.5 rounded-full bg-primary-subtle text-primary font-bold text-[11px]"
                                                                x-text="dayItem.day || `Day ${dIndex + 1}`"></span>
                                                            <input type="hidden"
                                                                :name="`routes[${index}][itinerary_days][${dIndex}][day]`"
                                                                :value="dayItem.day || `Day ${dIndex + 1}`">
                                                        </div>
                                                        <template x-if="r.itinerary_days && r.itinerary_days.length > 1">
                                                            <button type="button"
                                                                @click="removeItineraryDay(index, dIndex)"
                                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-rose-600 hover:bg-rose-50 text-[10px] font-bold transition-colors"
                                                                title="Hapus Hari Ini">
                                                                <svg class="w-3.5 h-3.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                <span>Hapus Hari</span>
                                                            </button>
                                                        </template>
                                                    </div>

                                                    <div>
                                                        <label class="block text-[10px] font-bold text-muted mb-1">Judul
                                                            Hari (Aktivitas Utama)</label>
                                                        <input type="text"
                                                            :name="`routes[${index}][itinerary_days][${dIndex}][title]`"
                                                            x-model="dayItem.title"
                                                            :placeholder="`Contoh: Day ${dIndex + 1}: Basecamp ke Camp Area`"
                                                            class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary font-bold">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-[10px] font-bold text-muted mb-1">Deskripsi
                                                            Ringkas Kegiatan</label>
                                                        <textarea rows="2" :name="`routes[${index}][itinerary_days][${dIndex}][desc]`" x-model="dayItem.desc"
                                                            placeholder="Deskripsi singkat perjalanan hari ini..."
                                                            class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary leading-relaxed"></textarea>
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-muted mb-1">Jadwal
                                                            Timeline (Format: HH:MM - Kegiatan)</label>
                                                        <textarea rows="4" :name="`routes[${index}][itinerary_days][${dIndex}][timeline]`" x-model="dayItem.timeline"
                                                            placeholder="08:00 - Registrasi di Basecamp&#10;09:00 - Mulai Trekking ke Pos 1&#10;12:30 - Makan Siang&#10;16:00 - Tiba di Camp Area"
                                                            class="w-full text-xs font-mono rounded-xl border border-hairline bg-surface-card p-2.5 text-ink focus:border-primary leading-relaxed"></textarea>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- View 2: Tek-tok (1 Hari) -->
                                    <div x-show="r.itinerary_type === 'tektok'" class="space-y-4">
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-hairline pb-3">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h5
                                                        class="text-xs font-extrabold text-ink-heading uppercase tracking-wider">
                                                        Timeline Rencana Pendakian Tek-tok (1 Hari)</h5>
                                                    <span
                                                        class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 font-bold text-[11px]">1D
                                                        (Tek-tok) • Tanpa Menginap</span>
                                                </div>
                                                <p class="text-[11px] text-muted">Jadwal pendakian cepat 1 hari langsung
                                                    turun tanpa tenda. Tuliskan jadwal kegiatan (1 baris per aktivitas:
                                                    <code class="font-mono bg-canvas px-1 py-0.5 rounded">01:00 - Start
                                                        Trekking Malam</code>)
                                                </p>
                                            </div>
                                        </div>

                                        <div class="p-5 rounded-2xl bg-canvas border border-hairline space-y-4">
                                            <div>
                                                <label class="block text-[11px] font-bold text-muted mb-1">Judul Itinerary
                                                    Tek-tok</label>
                                                <input type="text" :name="`routes[${index}][itinerary_tektok_title]`"
                                                    x-model="r.itinerary_tektok_title"
                                                    placeholder="Contoh: Itinerary 1D Tek-tok (Via Selo)"
                                                    class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2.5 text-ink focus:border-primary font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-muted mb-1">Deskripsi
                                                    Ringkas Tek-tok</label>
                                                <textarea rows="2" :name="`routes[${index}][itinerary_tektok_desc]`" x-model="r.itinerary_tektok_desc"
                                                    placeholder="Contoh: Pendakian cepat langsung turun dalam 1 hari tanpa mendirikan tenda..."
                                                    class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2.5 text-ink focus:border-primary leading-relaxed"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-muted mb-1">Jadwal Timeline
                                                    Lengkap (Format: HH:MM - Kegiatan)</label>
                                                <textarea rows="6" :name="`routes[${index}][itinerary_tektok_timeline]`" x-model="r.itinerary_tektok_timeline"
                                                    placeholder="00:00 - Registrasi & Cek Medis di Basecamp&#10;01:00 - Mulai Trekking Dini Hari Menuju Pos 2&#10;05:30 - Sunrise Spektakuler di Puncak Tertinggi&#10;07:30 - Foto Bersama & Mulai Turun&#10;12:00 - Tiba Kembali di Basecamp & Penutupan"
                                                    class="w-full text-xs font-mono rounded-xl border border-hairline bg-surface-card p-3 text-ink focus:border-primary leading-relaxed"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                </template>
            </div>
    </div>

    <!-- Section 4: Matriks Harga Bertingkat Kuota -->
    <div class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-hairline pb-4">
            <div>
                <h3 class="text-base font-extrabold text-ink-heading">4. Matriks Harga Bertingkat Kuota (Dynamic Tiering)
                </h3>
                <p class="text-xs text-muted">Tetapkan harga otomatis berdasarkan batas minimal kuota peserta yang
                    terkumpul</p>
            </div>
            <button type="button" @click="addTier()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-subtle text-primary text-xs font-bold hover:bg-primary hover:text-white transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Tier</span>
            </button>
        </div>

        <div class="space-y-4">
            <template x-for="(t, index) in priceTiers" :key="index">
                <div
                    class="p-4 rounded-2xl bg-canvas border border-hairline grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                    <!-- Min Pax -->
                    <div class="sm:col-span-5">
                        <label class="block text-[11px] font-bold text-muted mb-1">Minimal Peserta (≥ Pax)</label>
                        <input type="number" :name="`price_tiers[${index}][min_pax]`" x-model="t.min_pax"
                            min="1" required placeholder="Contoh: 1, 4, 7..."
                            class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary">
                    </div>

                    <!-- Price per Pax -->
                    <div class="sm:col-span-6">
                        <label class="block text-[11px] font-bold text-muted mb-1">Harga Final / Pax (Rp)</label>
                        <input type="number" :name="`price_tiers[${index}][price_per_pax]`" x-model="t.price_per_pax"
                            required placeholder="Contoh: 550000"
                            class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary font-bold">
                    </div>

                    <!-- Remove Button -->
                    <div class="sm:col-span-1 text-right pt-4 sm:pt-0">
                        <button type="button" @click="removeTier(index)"
                            class="p-2 text-muted hover:text-rose-600 rounded-lg transition-colors" title="Hapus Tier">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Section 5: Overview & Cerita Lengkap Ekspedisi -->
    <div class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 shadow-xs space-y-6">
        <div class="border-b border-hairline pb-4">
            <h3 class="text-base font-extrabold text-ink-heading">5. Ulasan Lengkap & Karakteristik Ekspedisi (Overview)
            </h3>
            <p class="text-xs text-muted">Deskripsi mendalam tentang panorama, bentang alam, dan pengalaman pendakian yang
                ditampilkan pada tab Overview pelanggan</p>
        </div>

        <div>
            <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                Ulasan & Cerita Pendakian (Overview Detail)
            </label>
            <textarea name="overview" rows="5"
                placeholder="Tuliskan ulasan komprehensif tentang keunikan vegetasi, sejarah geologis, pesona pemandangan sabana, dan sensasi summit attack..."
                class="w-full text-xs rounded-2xl border border-hairline bg-canvas p-4 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs leading-relaxed">{{ old('overview', 'Gunung ini menawarkan keindahan panorama alam luar biasa dengan padang vegetasi yang asri dan pemandangan lautan awan yang menakjubkan. Pendakian ini sangat ideal bagi pendaki yang menginginkan petualangan aman dengan standar SOP profesional MiddleTrip.') }}</textarea>
        </div>
    </div>

    <!-- Section 6: Fasilitas Ekspedisi (Include & Exclude) -->
    <div class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 shadow-xs space-y-6">
        <div class="border-b border-hairline pb-4">
            <h3 class="text-base font-extrabold text-ink-heading">6. Fasilitas Termasuk (Include) & Tidak Termasuk
                (Exclude)</h3>
            <p class="text-xs text-muted">Daftar item fasilitas dan akomodasi. Tuliskan 1 item per baris pada setiap kotak
                kategori</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left: Fasilitas Termasuk (Included) -->
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-emerald-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Fasilitas Termasuk
                            (Include)</h4>
                    </div>
                    <button type="button" @click="addFacilityIncluded()"
                        class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold hover:bg-emerald-600 hover:text-white transition-colors">
                        + Tambah Kategori
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(cat, index) in facilitiesIncluded" :key="index">
                        <div class="p-4 rounded-2xl bg-canvas border border-hairline space-y-2 relative group">
                            <div class="flex items-center justify-between gap-2">
                                <input type="text" :name="`facilities_included[${index}][category]`"
                                    x-model="cat.category" required placeholder="Nama Kategori (contoh: Akomodasi Camp)"
                                    class="w-full text-xs font-bold rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary">
                                <button type="button" @click="removeFacilityIncluded(index)"
                                    class="text-muted hover:text-rose-600 p-1 rounded transition-colors"
                                    title="Hapus Kategori">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <label class="block text-[10px] text-muted font-semibold">Daftar Item (1 baris per
                                fasilitas):</label>
                            <textarea :name="`facilities_included[${index}][items]`" x-model="cat.items" rows="3" required
                                placeholder="Item 1&#10;Item 2&#10;Item 3"
                                class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary leading-relaxed"></textarea>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right: Fasilitas Tidak Termasuk (Excluded) -->
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-rose-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <h4 class="text-xs font-bold text-rose-800 uppercase tracking-wider">Tidak Termasuk (Exclude)</h4>
                    </div>
                    <button type="button" @click="addFacilityExcluded()"
                        class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold hover:bg-rose-600 hover:text-white transition-colors">
                        + Tambah Kategori
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(cat, index) in facilitiesExcluded" :key="index">
                        <div class="p-4 rounded-2xl bg-canvas border border-hairline space-y-2 relative group">
                            <div class="flex items-center justify-between gap-2">
                                <input type="text" :name="`facilities_excluded[${index}][category]`"
                                    x-model="cat.category" required
                                    placeholder="Nama Kategori (contoh: Kebutuhan Pribadi)"
                                    class="w-full text-xs font-bold rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary">
                                <button type="button" @click="removeFacilityExcluded(index)"
                                    class="text-muted hover:text-rose-600 p-1 rounded transition-colors"
                                    title="Hapus Kategori">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                            <label class="block text-[10px] text-muted font-semibold">Daftar Item (1 baris per
                                fasilitas):</label>
                            <textarea :name="`facilities_excluded[${index}][items]`" x-model="cat.items" rows="3" required
                                placeholder="Item 1&#10;Item 2&#10;Item 3"
                                class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary leading-relaxed"></textarea>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Submit Bar -->
    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('admin.mountains.index') }}"
            class="px-6 py-2.5 rounded-full border border-hairline text-xs font-semibold text-ink hover:bg-canvas transition-colors">
            Batal
        </a>
        <button type="submit"
            class="px-8 py-2.5 rounded-full bg-primary text-white text-xs font-bold hover:bg-primary-hover transition-colors shadow-sm">
            Simpan Master Gunung
        </button>
    </div>
    </form>
    </div>
@endsection

@push('scripts')
    <script>
        function mountainCreateForm() {
            return {
                routes: @js($defaultRoutes),
                priceTiers: [{
                        min_pax: 1,
                        price_per_pax: 650000
                    },
                    {
                        min_pax: 4,
                        price_per_pax: 550000
                    },
                    {
                        min_pax: 7,
                        price_per_pax: 480000
                    }
                ],
                facilitiesIncluded: [{
                        category: 'Akomodasi Camp',
                        items: 'Tenda Kapasitas Fleksibel\nCommon Area (Flysheet & Camp Lamp)\nPeralatan Masak & Gas (Kompor / Nesting)\nSet Alat Makan & Minum'
                    },
                    {
                        category: 'Perizinan & Keamanan',
                        items: 'Tiket Masuk & SIMAKSI Resmi\nAsuransi Pendakian Resmi'
                    },
                    {
                        category: 'Perlengkapan Personal',
                        items: 'Sleeping Bag / Warm Polar\nMatras Busa\nJas Hujan & Emergency Blanket\nP3K Standar Pendakian'
                    }
                ],
                facilitiesExcluded: [{
                        category: 'Kebutuhan & Perlengkapan Pribadi',
                        items: 'Pakaian & Sepatu Pendakian Pribadi\nObat-obatan Pribadi Khusus'
                    },
                    {
                        category: 'Transportasi & Akses Awal',
                        items: 'Transportasi Kota Asal ke Meeting Point'
                    }
                ],
                addRoute() {
                    const isFirst = this.routes.length === 0;
                    this.routes.push({
                        name: '',
                        distance_km: '',
                        duration_hours: '',
                        grade: 'Grade A',
                        is_primary: isFirst,
                        isOpen: true,
                        activeSubTab: 'elevation',
                        checkpoints: [{
                                name: 'Basecamp',
                                elevation: ''
                            },
                            {
                                name: 'Pos 1',
                                elevation: ''
                            },
                            {
                                name: 'Pos 2',
                                elevation: ''
                            },
                            {
                                name: 'Puncak',
                                elevation: ''
                            }
                        ],
                        water_note: '',
                        wind_note: '',
                        signal_note: '',
                        itinerary_type: 'camping',
                        itinerary_days: [{
                                day: 'Day 1',
                                title: '',
                                desc: '',
                                timeline: ''
                            },
                            {
                                day: 'Day 2',
                                title: '',
                                desc: '',
                                timeline: ''
                            }
                        ],
                        itinerary_tektok_title: '',
                        itinerary_tektok_desc: '',
                        itinerary_tektok_timeline: ''
                    });
                },
                getRouteDurationLabel(r) {
                    if (!r || !r.itinerary_days || r.itinerary_days.length === 0) return '2D1N';
                    const count = r.itinerary_days.length;
                    if (count === 1) return '1D (Tek-tok)';
                    return `${count}D${count - 1}N`;
                },
                addItineraryDay(routeIndex) {
                    const r = this.routes[routeIndex];
                    if (!r.itinerary_days) r.itinerary_days = [];
                    const nextDayNum = r.itinerary_days.length + 1;
                    r.itinerary_days.push({
                        day: `Day ${nextDayNum}`,
                        title: `Day ${nextDayNum}: Eksplorasi Lanjutan & Summit`,
                        desc: 'Aktivitas pendakian hari ke-' + nextDayNum + ' menuju target pos selanjutnya.',
                        timeline: '06:00 - Bangun & Sarapan Pagi\n08:00 - Mulai Trekking\n12:00 - Istirahat & Makan Siang\n17:00 - Tiba di Camp & Istirahat'
                    });
                },
                removeItineraryDay(routeIndex, dayIndex) {
                    const r = this.routes[routeIndex];
                    if (r.itinerary_days && r.itinerary_days.length > 1) {
                        r.itinerary_days.splice(dayIndex, 1);
                        r.itinerary_days.forEach((item, idx) => {
                            item.day = `Day ${idx + 1}`;
                        });
                    }
                },
                removeRoute(index) {
                    if (this.routes.length > 1) {
                        this.routes.splice(index, 1);
                    }
                },
                toggleRoute(index) {
                    this.routes[index].isOpen = !this.routes[index].isOpen;
                },
                addRouteCheckpoint(routeIndex) {
                    this.routes[routeIndex].checkpoints.push({
                        name: '',
                        elevation: ''
                    });
                },
                removeRouteCheckpoint(routeIndex, cpIndex) {
                    if (this.routes[routeIndex].checkpoints.length > 2) {
                        this.routes[routeIndex].checkpoints.splice(cpIndex, 1);
                    }
                },
                addTier() {
                    let lastTier = this.priceTiers[this.priceTiers.length - 1];
                    let nextMin = lastTier ? parseInt(lastTier.min_pax) + 3 : 1;
                    this.priceTiers.push({
                        min_pax: nextMin,
                        price_per_pax: ''
                    });
                },
                removeTier(index) {
                    if (this.priceTiers.length > 1) {
                        this.priceTiers.splice(index, 1);
                    }
                },
                addFacilityIncluded() {
                    this.facilitiesIncluded.push({
                        category: '',
                        items: ''
                    });
                },
                removeFacilityIncluded(index) {
                    this.facilitiesIncluded.splice(index, 1);
                },
                addFacilityExcluded() {
                    this.facilitiesExcluded.push({
                        category: '',
                        items: ''
                    });
                },
                removeFacilityExcluded(index) {
                    this.facilitiesExcluded.splice(index, 1);
                }
            };
        }
    </script>
@endpush
