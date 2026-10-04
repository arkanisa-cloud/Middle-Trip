@extends('layouts.admin')

@section('title', 'Edit Gunung — ' . $mountain->name)
@section('header_title', 'Edit Destinasi: ' . $mountain->name)
@section('header_subtitle', 'Perbarui spesifikasi teknis rute, aturan tarif, overview, profil elevasi, dan fasilitas')

@section('content')
    <div x-data="mountainEditForm()" class="max-w-5xl mx-auto space-y-6">

        <!-- Page Header Banner -->
        <x-admin.page-header :title="'Edit Destinasi: ' . $mountain->name"
            subtitle="Perbarui spesifikasi teknis rute, aturan tarif, overview, profil elevasi, dan fasilitas ekspedisi."
            :backUrl="route('admin.mountains.index')" backLabel="Kembali ke Daftar Gunung" />

        <form method="POST" action="{{ route('admin.mountains.update', $mountain->id) }}" enctype="multipart/form-data"
            novalidate class="space-y-6">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs space-y-1">
                    <div class="flex items-center gap-2 font-bold text-sm text-rose-900">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
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
                <div class="border-b border-hairline pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-extrabold text-ink-heading">1. Informasi Umum Destinasi</h3>
                        <p class="text-xs text-muted">Data pokok gunung yang akan ditampilkan pada landing page dan katalog
                        </p>
                    </div>
                    <span
                        class="text-xs font-mono font-semibold px-2.5 py-1 rounded-full bg-canvas border border-hairline text-muted">
                        Slug: {{ $mountain->slug }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Nama Gunung <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $mountain->name) }}" required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                    </div>

                    <!-- Elevation -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Ketinggian (MDPL) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="elevation" value="{{ old('elevation', $mountain->elevation) }}" required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                    </div>

                    <!-- Province -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Provinsi / Wilayah <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="province" value="{{ old('province', $mountain->province) }}" required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                    </div>

                    <!-- Grade Selection (UI Kit Dropdown Varian 3 / Status Dot) -->
                    @php
                        $currentGradeVal = old('grade', $defaultGrade);
                        $gradeLabels = [
                            'Grade A' => [
                                'label' => 'Grade A – Pemula',
                                'dot' => 'bg-emerald-500',
                                'text' => 'text-emerald-700',
                            ],
                            'Grade B' => [
                                'label' => 'Grade B – Menengah',
                                'dot' => 'bg-amber-500',
                                'text' => 'text-amber-700',
                            ],
                            'Grade C' => [
                                'label' => 'Grade C – Ahli',
                                'dot' => 'bg-rose-500',
                                'text' => 'text-rose-700',
                            ],
                        ];
                        $currentGradeData = $gradeLabels[$currentGradeVal] ?? [
                            'label' => 'Grade A – Pemula',
                            'dot' => 'bg-emerald-500',
                            'text' => 'text-emerald-700',
                        ];
                    @endphp
                    <div x-data="{
                        open: false,
                        selectedVal: '{{ $currentGradeVal }}',
                        selectedLabel: '{{ $currentGradeData['label'] }}',
                        selectedDot: '{{ $currentGradeData['dot'] }}'
                    }" @click.outside="open = false" class="relative">
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Tingkat Kesulitan Induk (Grade) <span class="text-rose-500">*</span>
                        </label>
                        <input type="hidden" name="grade" :value="selectedVal" required>

                        <button type="button" @click="open = !open"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink flex items-center justify-between cursor-pointer focus:border-primary shadow-xs font-semibold transition select-none">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" :class="selectedDot"></span>
                                <span x-text="selectedLabel" class="text-ink-heading"></span>
                            </span>
                            <svg class="w-4 h-4 text-muted transition-transform duration-200 shrink-0"
                                :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            class="absolute left-0 right-0 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 text-xs">
                            <div
                                class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider border-b border-hairline/60 mb-1">
                                Tingkat Kesulitan
                            </div>
                            <button type="button"
                                @click="selectedVal = 'Grade A'; selectedLabel = 'Grade A – Pemula'; selectedDot = 'bg-emerald-500'; open = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="selectedVal === 'Grade A' ? 'bg-primary-subtle/50 font-bold' : ''">
                                <span class="text-emerald-700">Grade A – Pemula</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            </button>
                            <button type="button"
                                @click="selectedVal = 'Grade B'; selectedLabel = 'Grade B – Menengah'; selectedDot = 'bg-amber-500'; open = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="selectedVal === 'Grade B' ? 'bg-primary-subtle/50 font-bold' : ''">
                                <span class="text-amber-700">Grade B – Menengah</span>
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            </button>
                            <button type="button"
                                @click="selectedVal = 'Grade C'; selectedLabel = 'Grade C – Ahli'; selectedDot = 'bg-rose-500'; open = false;"
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="selectedVal === 'Grade C' ? 'bg-primary-subtle/50 font-bold' : ''">
                                <span class="text-rose-700">Grade C – Ahli</span>
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Cover Image Upload -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Upload Foto Sampul (Cover Image)
                        </label>
                        <div x-data="{
                            preview: '{{ old('cover_image', $mountain->cover_image) }}',
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
                                        accept="image/*"
                                        class="w-full text-xs text-muted file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-subtle file:text-primary hover:file:bg-primary hover:file:text-white file:transition-colors file:cursor-pointer border border-hairline rounded-xl bg-canvas p-1.5 focus:border-primary shadow-xs">
                                    <p class="text-[11px] text-muted mt-1.5">Kosongkan jika tidak ingin mengubah foto
                                        sampul. Format didukung: JPG, PNG, WEBP (Maksimal 5MB)</p>
                                </div>
                            </div>
                            <input type="hidden" name="cover_image" :value="preview">
                        </div>
                    </div>

                    <!-- Gallery Photos Section -->
                    <div class="md:col-span-2 border-t border-hairline pt-4" x-data="{
                        existingItems: {{ Js::from($mountain->gallery ?? []) }},
                        newFiles: [],
                        removeExisting(index) {
                            this.existingItems.splice(index, 1);
                        },
                        clearAllExisting() {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Hapus Seluruh Galeri?',
                                    text: 'Semua foto galeri gunung ini akan ditandai untuk dihapus saat form disimpan.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#1B4D3E',
                                    cancelButtonColor: '#94A3B8',
                                    confirmButtonText: 'Ya, Hapus Semua',
                                    cancelButtonText: 'Batal',
                                    customClass: {
                                        popup: 'rounded-3xl shadow-xl font-sans',
                                        confirmButton: 'rounded-xl font-bold px-5 py-2.5',
                                        cancelButton: 'rounded-xl font-bold px-5 py-2.5'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        this.existingItems = [];
                                        if (window.toastr) toastr.info('Seluruh foto galeri dihapus');
                                    }
                                });
                            } else if (confirm('Yakin ingin menghapus seluruh foto galeri gunung ini?')) {
                                this.existingItems = [];
                            }
                        },
                        handleNewFiles(e) {
                            const selectedFiles = Array.from(e.target.files);
                            if (!selectedFiles.length) return;
                    
                            selectedFiles.forEach((f) => {
                                this.newFiles.push({
                                    file: f,
                                    preview: URL.createObjectURL(f),
                                    caption: ''
                                });
                            });
                    
                            if (window.DataTransfer && this.$refs.newFileInput) {
                                const dt = new DataTransfer();
                                this.newFiles.forEach(item => dt.items.add(item.file));
                                this.$refs.newFileInput.files = dt.files;
                            }
                        },
                        removeNewFile(index) {
                            this.newFiles.splice(index, 1);
                            if (window.DataTransfer && this.$refs.newFileInput) {
                                const dt = new DataTransfer();
                                this.newFiles.forEach(item => dt.items.add(item.file));
                                this.$refs.newFileInput.files = dt.files;
                            }
                        },
                        clearAllNewFiles() {
                            this.newFiles = [];
                            if (this.$refs.newFileInput) {
                                this.$refs.newFileInput.value = '';
                            }
                        }
                    }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                            <div>
                                <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider">
                                    Galeri Foto Ekspedisi (Bento Grid & Lightbox)
                                </label>
                                <p class="text-[11px] text-muted">Kelola foto pendukung ekspedisi. Foto-foto ini akan
                                    tampil pada 4 kartu samping & modal lightbox detail ekspedisi.</p>
                            </div>
                            <template x-if="existingItems.length > 0">
                                <button type="button" @click="clearAllExisting()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold hover:bg-rose-600 hover:text-white transition-colors self-start sm:self-auto shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Hapus Semua Foto Galeri</span>
                                </button>
                            </template>
                        </div>

                        <!-- Existing Gallery List -->
                        <template x-if="existingItems.length > 0">
                            <div class="mb-5 bg-canvas/60 p-4 rounded-2xl border border-hairline">
                                <div class="flex items-center justify-between mb-3">
                                    <span
                                        class="text-xs font-bold text-ink-heading uppercase tracking-wider flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                                        <span>Foto Galeri Tersimpan (<span x-text="existingItems.length"></span>
                                            Foto)</span>
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                    <template x-for="(item, index) in existingItems" :key="index">
                                        <div
                                            class="relative bg-surface-card rounded-2xl border border-hairline overflow-hidden p-2.5 shadow-xs flex flex-col justify-between">
                                            <div class="aspect-4/3 rounded-xl overflow-hidden mb-2 bg-gray-100 relative">
                                                <img :src="item.url" class="w-full h-full object-cover">
                                                <button type="button" @click="removeExisting(index)" title="Hapus Foto"
                                                    class="absolute top-2 right-2 p-1.5 bg-rose-600 text-white rounded-lg hover:bg-rose-700 shadow-md transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <input type="hidden" :name="'existing_gallery[' + index + '][url]'"
                                                :value="item.url">
                                            <input type="text" :name="'existing_gallery[' + index + '][caption]'"
                                                x-model="item.caption" placeholder="Keterangan foto..."
                                                class="w-full text-[11px] rounded-lg border border-hairline bg-canvas p-1.5 text-ink focus:border-primary shadow-2xs mb-2">
                                            <button type="button" @click="removeExisting(index)"
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
                            </div>
                        </template>

                        <template x-if="existingItems.length === 0">
                            <div
                                class="mb-4 p-4 rounded-2xl border border-dashed border-hairline bg-canvas text-center text-xs text-muted">
                                Belum ada foto galeri pendukung tersimpan. Silakan pilih foto baru di bawah ini.
                            </div>
                        </template>

                        <!-- Add New Files -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-ink-heading uppercase tracking-wider">Tambah
                                    Foto Baru ke Galeri</label>
                                <template x-if="newFiles.length > 0">
                                    <button type="button" @click="clearAllNewFiles()"
                                        class="text-[11px] font-bold text-rose-600 hover:text-rose-800 transition-colors">
                                        Batalkan Semua Foto Baru (<span x-text="newFiles.length"></span>)
                                    </button>
                                </template>
                            </div>
                            <input type="file" name="gallery_files[]" x-ref="newFileInput" @change="handleNewFiles"
                                accept="image/*" multiple
                                class="w-full text-xs text-muted file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-subtle file:text-primary hover:file:bg-primary hover:file:text-white file:transition-colors file:cursor-pointer border border-hairline rounded-xl bg-canvas p-1.5 focus:border-primary shadow-xs">

                            <template x-if="newFiles.length > 0">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 pt-2">
                                    <template x-for="(item, index) in newFiles" :key="index">
                                        <div
                                            class="relative bg-surface-card rounded-2xl border border-hairline overflow-hidden p-2.5 shadow-xs flex flex-col justify-between">
                                            <div class="aspect-4/3 rounded-xl overflow-hidden mb-2 bg-gray-100 relative">
                                                <img :src="item.preview" class="w-full h-full object-cover">
                                                <button type="button" @click="removeNewFile(index)"
                                                    title="Hapus Foto Baru"
                                                    class="absolute top-2 right-2 p-1.5 bg-rose-600 text-white rounded-lg hover:bg-rose-700 shadow-md transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <input type="text" name="gallery_file_captions[]" x-model="item.caption"
                                                placeholder="Keterangan foto baru..."
                                                class="w-full text-[11px] rounded-lg border border-hairline bg-canvas p-1.5 text-ink focus:border-primary shadow-2xs mb-2">
                                            <button type="button" @click="removeNewFile(index)"
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
                        <input type="number" name="base_price" value="{{ old('base_price', $mountain->base_price) }}"
                            required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Harga dasar open trip paket camping</span>
                    </div>

                    <!-- Private Price (Camping Private) -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Harga Camping Private (Rp)
                        </label>
                        <input type="number" name="price_private"
                            value="{{ old('price_private', $mountain->price_private) }}"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Harga dasar private trip paket camping</span>
                    </div>

                    <!-- Price Tektok (Open Trip) -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Harga Tek-tok Open (Rp)
                        </label>
                        <input type="number" name="price_tektok"
                            value="{{ old('price_tektok', $mountain->price_tektok) }}" placeholder="400000"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Harga dasar open trip paket 1 hari</span>
                    </div>

                    <!-- Price Tektok Private -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Harga Tek-tok Private (Rp)
                        </label>
                        <input type="number" name="price_private_tektok"
                            value="{{ old('price_private_tektok', $mountain->price_private_tektok) }}"
                            placeholder="850000"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Harga dasar private trip paket 1 hari</span>
                    </div>

                    <!-- Booking Fee per Pax -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Booking Fee DP / Pax (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="booking_fee_per_pax"
                            value="{{ old('booking_fee_per_pax', $mountain->booking_fee_per_pax) }}" required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-amber-600 font-medium mt-1 block">Maksimal 50% (setengah) dari
                            harga dasar trip</span>
                    </div>

                    <!-- Price Lock Days -->
                    <div>
                        <label class="block text-xs font-bold text-ink-heading uppercase tracking-wider mb-2">
                            Price Lock (H-X Hari) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="price_lock_days_before_departure"
                            value="{{ old('price_lock_days_before_departure', $mountain->price_lock_days_before_departure) }}"
                            min="1" max="30" required
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs">
                        <span class="text-[10.5px] text-muted mt-1 block">Hari sebelum berangkat harga dikunci</span>
                    </div>
                </div>

                <!-- Toggles -->
                <div class="space-y-3 pt-4 border-t border-hairline" x-data="{ isFeatured: {{ old('is_featured', $mountain->is_featured) ? 'true' : 'false' }} }">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-ink-heading">
                            <input type="checkbox" name="has_open_trip" value="1"
                                {{ old('has_open_trip', $mountain->has_open_trip) ? 'checked' : '' }}
                                class="rounded border-hairline text-primary focus:ring-primary">
                            <span>Open Trip</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-ink-heading">
                            <input type="checkbox" name="has_private_trip" value="1"
                                {{ old('has_private_trip', $mountain->has_private_trip) ? 'checked' : '' }}
                                class="rounded border-hairline text-primary focus:ring-primary">
                            <span>Private Trip</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-ink-heading">
                            <input type="checkbox" name="is_featured" value="1" x-model="isFeatured"
                                class="rounded border-hairline text-primary focus:ring-primary">
                            <span>Featured di Home</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-ink-heading">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $mountain->is_active) ? 'checked' : '' }}
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
                                <option value="1"
                                    {{ old('featured_order', $mountain->featured_order ?? 1) == 1 ? 'selected' : '' }}>Slot
                                    1: Hero Utama (Kiri Lebar - Span 7)</option>
                                <option value="2"
                                    {{ old('featured_order', $mountain->featured_order) == 2 ? 'selected' : '' }}>Slot 2:
                                    Kartu Kanan Atas (Span 5)</option>
                                <option value="3"
                                    {{ old('featured_order', $mountain->featured_order) == 3 ? 'selected' : '' }}>Slot 3:
                                    Kartu Kanan Bawah (Span 5)</option>
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
                            Itinerary (Per Rute)</h3>
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
                    <template x-for="(r, index) in routes" :key="index">
                        <div class="rounded-3xl border border-hairline bg-canvas overflow-hidden shadow-xs transition-all">
                            <input type="hidden" :name="`routes[${index}][id]`" :value="r.id">

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
                                    <template x-if="r.has_active_bookings || r.has_bookings">
                                        <span
                                            class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold flex items-center gap-1 shrink-0"
                                            title="Jalur ini tidak bisa dihapus karena sedang dipesan oleh pendaki">
                                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span><span x-text="r.active_bookings_count || r.bookings_count"></span>
                                                Booking</span>
                                        </span>
                                    </template>
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
                                        :class="(r.has_active_bookings || r.has_bookings) ?
                                        'text-amber-500 hover:text-amber-700 hover:bg-amber-50 cursor-not-allowed' :
                                        'text-muted hover:text-rose-600 hover:bg-rose-50'"
                                        class="p-2 rounded-xl transition-colors"
                                        :title="(r.has_active_bookings || r.has_bookings) ?
                                        'Jalur sedang dipesan (Tidak dapat dihapus)' : 'Hapus Jalur'">
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
                                        placeholder="Via Selo"
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
                                                        x-model="cp.name" placeholder="Basecamp / Pos 1 / Sabana / Puncak"
                                                        class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary">
                                                </div>
                                                <div class="sm:col-span-4">
                                                    <label class="block text-[10px] font-bold text-muted mb-1">Ketinggian
                                                        (MDPL)</label>
                                                    <input type="number"
                                                        :name="`routes[${index}][checkpoints][${cpIndex}][elevation]`"
                                                        x-model="cp.elevation" placeholder="1800"
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
                                            ⛺ Itinerary Camping (Multi-Day)
                                        </button>
                                        <button type="button" @click="r.itinerary_type = 'tektok'"
                                            :class="r.itinerary_type === 'tektok' ? 'bg-primary text-white shadow-xs' :
                                                'text-muted hover:text-ink'"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all">
                                            ⚡ Itinerary Tek-tok (1 Hari)
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
                                                            <span class="text-[10px] text-muted"
                                                                x-text="`Hari ke-${dIndex + 1}`"></span>
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
                                                            :placeholder="`Day ${dIndex + 1}: Basecamp ke Camp Area`"
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
                                                    placeholder="Itinerary 1D Tek-tok (Via Selo)"
                                                    class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2.5 text-ink focus:border-primary font-bold">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-muted mb-1">Deskripsi
                                                    Ringkas Tek-tok</label>
                                                <textarea rows="2" :name="`routes[${index}][itinerary_tektok_desc]`" x-model="r.itinerary_tektok_desc"
                                                    placeholder="Pendakian cepat langsung turun dalam 1 hari tanpa mendirikan tenda..."
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
                    <input type="hidden" :name="`price_tiers[${index}][id]`" :value="t.id">

                    <!-- Min Pax -->
                    <div class="sm:col-span-5">
                        <label class="block text-[11px] font-bold text-muted mb-1">Minimal Peserta (≥ Pax)</label>
                        <input type="number" :name="`price_tiers[${index}][min_pax]`" x-model="t.min_pax"
                            min="1" required
                            class="w-full text-xs rounded-xl border border-hairline bg-surface-card p-2 text-ink focus:border-primary">
                    </div>

                    <!-- Price per Pax -->
                    <div class="sm:col-span-6">
                        <label class="block text-[11px] font-bold text-muted mb-1">Harga Final / Pax (Rp)</label>
                        <input type="number" :name="`price_tiers[${index}][price_per_pax]`" x-model="t.price_per_pax"
                            required
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
                class="w-full text-xs rounded-2xl border border-hairline bg-canvas p-4 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-xs leading-relaxed">{{ old('overview', $mountain->overview) }}</textarea>
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
                                    x-model="cat.category" required placeholder="Nama Kategori (Akomodasi Camp)"
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
                                    x-model="cat.category" required placeholder="Nama Kategori (Kebutuhan Pribadi)"
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
            Perbarui Master Gunung
        </button>
    </div>
    </form>
    </div>
@endsection

@push('scripts')
    <script>
        function mountainEditForm() {
            return {
                routes: @js($routesData),
                priceTiers: @js(
    $mountain->priceTiers->map(
        fn($t) => [
            'id' => $t->id,
            'min_pax' => $t->min_pax,
            'price_per_pax' => $t->price_per_pax,
        ],
    ),
),
                facilitiesIncluded: @js($facilitiesIncluded),
                facilitiesExcluded: @js($facilitiesExcluded),
                addRoute() {
                    this.routes.push({
                        id: null,
                        name: '',
                        distance_km: '',
                        duration_hours: '',
                        grade: 'Grade A',
                        is_primary: false,
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
                                name: 'Pos 3',
                                elevation: ''
                            },
                            {
                                name: 'Puncak',
                                elevation: ''
                            }
                        ],
                        water_note: 'Pos 2 (Mata Air Alami Melimpah)',
                        wind_note: 'Sabana / Punggungan Terbuka',
                        signal_note: 'Stabil di Basecamp & Pos 1',
                        itinerary_type: 'camping',
                        itinerary_tektok_title: 'Itinerary 1D Tek-tok',
                        itinerary_tektok_desc: 'Pendakian cepat langsung turun dalam 1 hari tanpa mendirikan tenda.',
                        itinerary_tektok_timeline: '00:00 - Registrasi & Cek Medis di Basecamp\n01:00 - Mulai Trekking Dini Hari Menuju Pos 2\n05:30 - Sunrise Spektakuler di Puncak Tertinggi\n07:30 - Foto Bersama & Mulai Turun\n12:00 - Tiba Kembali di Basecamp & Penutupan',
                        itinerary_days: [{
                                day: 'Day 1',
                                title: 'Day 1: Basecamp ke Camp Area',
                                desc: 'Mulai pendakian dari Basecamp melewati perkebunan dan vegetasi hutan, beristirahat di pos tengah dan mendirikan tenda di camp area.',
                                timeline: '08:00 - Registrasi & Persiapan di Basecamp\n09:00 - Mulai Trekking Menuju Pos 1 & 2\n12:30 - Makan Siang & Istirahat di Pos Tengah\n16:00 - Tiba di Camp Area & Dirikan Tenda'
                            },
                            {
                                day: 'Day 2',
                                title: 'Day 2: Summit Push & Turun Kembali',
                                desc: 'Bangun dini hari untuk summit push menikmati sunrise di puncak tertinggi, sarapan hangat, lalu berkemas turun kembali ke basecamp.',
                                timeline: '03:30 - Summit Push Menuju Puncak\n05:30 - Sunrise Spektakuler di Puncak\n08:00 - Kembali ke Camp, Sarapan & Packing\n10:00 - Perjalanan Turun Menuju Basecamp\n14:00 - Tiba di Basecamp & Penutupan Trip'
                            }
                        ]
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
                    let r = this.routes[index];
                    if (r && (r.has_active_bookings || r.has_bookings)) {
                        let count = r.active_bookings_count || r.bookings_count;
                        if (window.toastr) {
                            toastr.error(
                                `Jalur "${r.name || 'Jalur'}" tidak dapat dihapus karena sedang ada pemesanan (${count} booking aktif) oleh pendaki!`,
                                'Gagal Menghapus Jalur'
                            );
                        } else {
                            alert(
                                `Jalur "${r.name || 'Jalur'}" tidak dapat dihapus karena sedang ada pemesanan (${count} booking aktif) oleh pendaki!`
                            );
                        }
                        return;
                    }
                    if (this.routes.length > 1) {
                        this.routes.splice(index, 1);
                        if (window.toastr) {
                            toastr.info(`Jalur berhasil dihapus dari daftar form.`);
                        }
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
                        id: null,
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
