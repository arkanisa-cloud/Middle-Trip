@extends('layouts.admin')

@section('title', 'Titik Kumpul Shuttle')
@section('header_title', 'Titik Kumpul Shuttle & Antar-Jemput')
@section('header_subtitle', 'Kelola titik penjemputan resmi (stasiun, bandara, basecamp) dan biaya shuttle per orang')

@section('content')
    <div x-data="{ addModalOpen: false, editModalOpen: false, activePoint: {} }" class="space-y-6">

        <!-- Page Header -->
        <x-admin.page-header title="Titik Kumpul Shuttle"
            subtitle="Kelola titik penjemputan resmi rombongan (stasiun, bandara, terminal, basecamp) dan biaya shuttle per orang.">
            <x-slot:actions>
                <button type="button" @click="addModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-xs font-semibold font-outfit hover:bg-primary-hover transition-colors shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Titik Kumpul</span>
                </button>
            </x-slot:actions>
        </x-admin.page-header>

        <!-- Mountain Filter Bar (UI Kit Dropdown Varian 4 / Toolbar Dropdown) -->
        <div class="flex items-center justify-between gap-4">
            <form id="mountain-filter-form" method="GET" action="{{ route('admin.meeting-points.index') }}" class="flex items-center gap-2.5">
                <span class="text-xs font-semibold font-outfit text-muted">Filter Destinasi:</span>
                
                @php
                    $activeMountain = $mountains->firstWhere('id', $mountainId);
                    $selectedLabel = $activeMountain ? $activeMountain->name : 'Semua Gunung';
                @endphp

                <div x-data="{ open: false, selected: '{{ $mountainId }}', label: '{{ $selectedLabel }}' }" 
                     @click.outside="open = false" 
                     class="relative">
                    <input type="hidden" name="mountain_id" :value="selected" id="filter-mountain-id">
                    
                    <button type="button" 
                            @click="open = !open" 
                            class="bg-surface-card hover:bg-gray-50 text-ink text-xs font-semibold px-4 py-2 rounded-full border border-hairline flex items-center gap-2.5 transition cursor-pointer select-none shadow-xs">
                        <span x-text="label" class="block truncate max-w-[180px]"></span>
                        <svg class="w-3.5 h-3.5 text-muted transition-transform duration-200 shrink-0" 
                             :class="open ? 'rotate-180' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Elevated Menu -->
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute left-0 mt-2 w-64 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 max-h-60 overflow-y-auto text-xs">
                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider border-b border-hairline/60 mb-1">
                            Pilih Destinasi Gunung
                        </div>
                        <button type="button" 
                                @click="selected = ''; label = 'Semua Gunung'; open = false; $nextTick(() => $el.closest('form').submit());" 
                                class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                :class="selected === '' ? 'bg-primary-subtle/50 text-primary font-bold' : ''">
                            <span>Semua Gunung</span>
                            <span class="text-[10px] text-muted font-normal">Seluruh Wilayah</span>
                        </button>
                        @foreach ($mountains as $m)
                            <button type="button" 
                                    @click="selected = '{{ $m->id }}'; label = '{{ $m->name }}'; open = false; $nextTick(() => $el.closest('form').submit());" 
                                    class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                    :class="selected == '{{ $m->id }}' ? 'bg-primary-subtle/50 text-primary font-bold' : ''">
                                <span>{{ $m->name }}</span>
                                <span class="text-[10px] text-muted font-normal">{{ $m->province }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-surface-card border border-hairline rounded-3xl shadow-xs overflow-hidden">
            @if ($meetingPoints->isEmpty())
                <div class="py-16 text-center text-muted">
                    <svg class="w-12 h-12 mx-auto text-muted-soft mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-sm font-semibold font-outfit text-ink-heading">Belum ada titik kumpul shuttle.</p>
                    <p class="text-xs text-muted mt-1">Tambahkan meeting point (seperti stasiun kereta atau basecamp) untuk
                        gunung.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-canvas border-b border-hairline text-muted font-semibold font-outfit text-xs">
                            <tr>
                                <th class="py-3.5 px-5">Nama Titik Kumpul</th>
                                <th class="py-3.5 px-5">Destinasi Gunung</th>
                                <th class="py-3.5 px-5">Tipe Lokasi</th>
                                <th class="py-3.5 px-5">Biaya Tambahan / Pax</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-hairline">
                            @foreach ($meetingPoints as $p)
                                <tr class="hover:bg-canvas/50 transition-colors">
                                    <td class="py-4 px-5 font-bold text-ink-heading">
                                        {{ $p->name }}
                                        @if ($p->is_default)
                                            <span
                                                class="ml-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-primary-subtle text-primary border border-primary/20">
                                                Default
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 font-semibold text-ink">
                                        {{ $p->mountain->name ?? '-' }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-canvas border border-hairline capitalize text-ink">
                                            {{ $p->location_type }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 font-bold text-ink-heading">
                                        @if ($p->additional_price_per_pax > 0)
                                            + Rp {{ number_format($p->additional_price_per_pax, 0, ',', '.') }}
                                        @else
                                            <span class="text-emerald-600">Gratis (Rp 0)</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5">
                                        <span
                                            class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 text-right">
                                        <form method="POST" action="{{ route('admin.meeting-points.destroy', $p->id) }}"
                                              data-confirm="Apakah Anda yakin ingin menghapus titik kumpul '{{ $p->name }}'?"
                                              data-title="Hapus Titik Kumpul"
                                              class="confirm-delete inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-lg border border-hairline text-ink hover:text-rose-600 hover:border-rose-400 transition-colors"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($meetingPoints->hasPages())
                    <div class="p-4 border-t border-hairline">
                        {{ $meetingPoints->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Modal: Tambah Titik Kumpul (UI Kit Compliant Form Dropdowns) -->
        <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="addModalOpen = false"></div>
            <div
                class="relative bg-surface-card rounded-3xl border border-hairline shadow-xl max-w-md w-full p-6 space-y-5 z-10">
                <div class="flex items-center justify-between border-b border-hairline pb-3">
                    <h3 class="text-base font-extrabold font-outfit text-ink-heading">Tambah Titik Kumpul Shuttle</h3>
                    <button type="button" @click="addModalOpen = false"
                        class="text-muted hover:text-ink text-lg">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.meeting-points.store') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Mountain Custom Select -->
                    <div x-data="{ 
                        open: false, 
                        selectedId: '{{ $mountains->first()->id ?? '' }}', 
                        selectedName: '{{ $mountains->first()->name ?? 'Pilih Gunung' }}' 
                    }" @click.outside="open = false" class="relative">
                        <label class="block text-[11px] font-bold text-muted uppercase mb-1">Destinasi Gunung <span class="text-rose-500">*</span></label>
                        <input type="hidden" name="mountain_id" :value="selectedId" required>
                        
                        <button type="button" @click="open = !open"
                                class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink flex items-center justify-between cursor-pointer focus:border-primary transition select-none">
                            <span x-text="selectedName" class="font-semibold text-ink-heading block truncate"></span>
                            <svg class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" 
                                 :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute left-0 right-0 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 max-h-52 overflow-y-auto text-xs">
                            <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider border-b border-hairline/60 mb-1">
                                Pilih Destinasi Gunung
                            </div>
                            @foreach ($mountains as $m)
                                <button type="button" 
                                        @click="selectedId = '{{ $m->id }}'; selectedName = '{{ $m->name }}'; open = false;"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer"
                                        :class="selectedId == '{{ $m->id }}' ? 'bg-primary-subtle/50 text-primary font-bold' : ''">
                                    <span>{{ $m->name }}</span>
                                    <span class="text-[10px] text-muted font-normal">{{ $m->province }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-muted uppercase mb-1">Nama Titik Kumpul <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Stasiun Solo Balapan"
                            class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-2xs font-semibold">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <!-- Location Type Custom Dropdown -->
                        <div x-data="{ 
                            open: false, 
                            selectedVal: 'basecamp', 
                            selectedLabel: 'Basecamp' 
                        }" @click.outside="open = false" class="relative">
                            <label class="block text-[11px] font-bold text-muted uppercase mb-1">Tipe Lokasi <span class="text-rose-500">*</span></label>
                            <input type="hidden" name="location_type" :value="selectedVal" required>

                            <button type="button" @click="open = !open"
                                    class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink flex items-center justify-between cursor-pointer focus:border-primary transition select-none">
                                <span x-text="selectedLabel" class="font-semibold text-ink-heading block truncate"></span>
                                <svg class="w-4 h-4 text-muted transition-transform duration-200 shrink-0" 
                                     :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 class="absolute left-0 right-0 mt-2 bg-white border border-hairline rounded-2xl shadow-xl py-2 z-50 text-xs">
                                <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-muted-soft tracking-wider border-b border-hairline/60 mb-1">
                                    Tipe Titik Kumpul
                                </div>
                                <button type="button" @click="selectedVal = 'basecamp'; selectedLabel = 'Basecamp'; open = false;"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                                    <span>Basecamp</span>
                                    <span class="text-[10px] text-emerald-700 font-bold">Gratis</span>
                                </button>
                                <button type="button" @click="selectedVal = 'station'; selectedLabel = 'Stasiun Kereta'; open = false;"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                                    <span>Stasiun Kereta</span>
                                    <span class="text-[10px] text-muted font-normal">Transit</span>
                                </button>
                                <button type="button" @click="selectedVal = 'airport'; selectedLabel = 'Bandara Udara'; open = false;"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                                    <span>Bandara Udara</span>
                                    <span class="text-[10px] text-muted font-normal">Flight</span>
                                </button>
                                <button type="button" @click="selectedVal = 'terminal'; selectedLabel = 'Terminal Bus'; open = false;"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                                    <span>Terminal Bus</span>
                                    <span class="text-[10px] text-muted font-normal">Bus</span>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-muted uppercase mb-1">Biaya Shuttle / Pax (Rp)</label>
                            <input type="number" name="additional_price_per_pax" value="0" min="0" required
                                class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary font-bold shadow-2xs">
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-ink-heading pt-1">
                            <input type="checkbox" name="is_default" value="1"
                                class="rounded border-hairline text-primary focus:ring-primary">
                            <span>Jadikan Pilihan Titik Kumpul Default</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-hairline">
                        <button type="button" @click="addModalOpen = false"
                            class="px-4 py-2 rounded-full border border-hairline text-xs font-semibold hover:bg-canvas">Batal</button>
                        <button type="submit"
                            class="px-5 py-2 rounded-full bg-primary text-white text-xs font-bold hover:bg-primary-hover shadow-xs">Simpan Titik Kumpul</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
