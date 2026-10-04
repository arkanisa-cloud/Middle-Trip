@extends('layouts.admin')

@section('title', 'Add-ons Sewa Alat')
@section('header_title', 'Katalog Perlengkapan & Add-ons Sewa')
@section('header_subtitle', 'Kelola perlengkapan sewa pendakian (trekking pole, headlamp, sleeping bag, hydropack)')

@section('content')
    <div x-data="{ addModalOpen: false }" class="space-y-6">

        <!-- Page Header -->
        <x-admin.page-header title="Add-ons Sewa Alat"
            subtitle="Kelola katalog inventaris perlengkapan sewa pendakian (trekking pole, tenda, sleeping bag, hydropack) untuk kebutuhan pendaki.">
            <x-slot:actions>
                <button type="button" @click="addModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-xs font-semibold font-outfit hover:bg-primary-hover transition-colors shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Alat Sewa</span>
                </button>
            </x-slot:actions>
        </x-admin.page-header>

        <!-- Table Card -->
        <div class="bg-surface-card border border-hairline rounded-3xl shadow-xs overflow-hidden">
            @if ($addons->isEmpty())
                <div class="py-16 text-center text-muted">
                    <svg class="w-12 h-12 mx-auto text-muted-soft mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p class="text-sm font-semibold font-outfit text-ink-heading">Belum ada perlengkapan sewa.</p>
                    <p class="text-xs text-muted mt-1">Tambahkan barang inventaris yang disewakan untuk pendaki.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-canvas border-b border-hairline text-muted font-semibold font-outfit text-xs">
                            <tr>
                                <th class="py-3.5 px-5">Nama Perlengkapan</th>
                                <th class="py-3.5 px-5">Kategori</th>
                                <th class="py-3.5 px-5">Harga Sewa</th>
                                <th class="py-3.5 px-5">Ketersediaan</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-hairline">
                            @foreach ($addons as $a)
                                <tr class="hover:bg-canvas/50 transition-colors">
                                    <td class="py-4 px-5 font-bold text-ink-heading">
                                        {{ $a->name }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-canvas border border-hairline capitalize text-ink">
                                            {{ $a->category }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 font-bold text-ink-heading">
                                        Rp {{ number_format($a->price, 0, ',', '.') }}
                                        <span class="block text-[10px] text-muted font-normal">per trip</span>
                                    </td>
                                    <td class="py-4 px-5">
                                        @if ($a->is_active)
                                            <span
                                                class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disewakan
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 text-[11px] font-bold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 text-right">
                                        <form method="POST" action="{{ route('admin.addons.destroy', $a->id) }}"
                                              data-confirm="Apakah Anda yakin ingin menghapus alat sewa '{{ $a->name }}'?"
                                              data-title="Hapus Perlengkapan Sewa"
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

                 @if ($addons->hasPages())
                     <div class="p-4 border-t border-hairline">
                         {{ $addons->links() }}
                     </div>
                 @endif
             @endif
         </div>

         <!-- Modal: Tambah Addon (UI Kit Compliant Custom Dropdown) -->
         <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
             <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="addModalOpen = false"></div>
             <div
                 class="relative bg-surface-card rounded-3xl border border-hairline shadow-xl max-w-md w-full p-6 space-y-5 z-10">
                 <div class="flex items-center justify-between border-b border-hairline pb-3">
                     <h3 class="text-base font-extrabold font-outfit text-ink-heading">Tambah Perlengkapan Sewa</h3>
                     <button type="button" @click="addModalOpen = false"
                         class="text-muted hover:text-ink text-lg">&times;</button>
                 </div>

                 <form method="POST" action="{{ route('admin.addons.store') }}" class="space-y-4">
                     @csrf
                     <div>
                         <label class="block text-[11px] font-bold text-muted uppercase mb-1">Nama Alat <span class="text-rose-500">*</span></label>
                         <input type="text" name="name" required placeholder="Trekking Pole Carbon"
                             class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary shadow-2xs font-semibold">
                     </div>

                     <div class="grid grid-cols-2 gap-3">
                         <!-- Custom Category Dropdown -->
                         <div x-data="{ 
                             open: false, 
                             selectedVal: 'gear', 
                             selectedLabel: 'Alat Pribadi (Gear)' 
                         }" @click.outside="open = false" class="relative">
                             <label class="block text-[11px] font-bold text-muted uppercase mb-1">Kategori <span class="text-rose-500">*</span></label>
                             <input type="hidden" name="category" :value="selectedVal" required>

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
                                     Kategori Perlengkapan
                                 </div>
                                 <button type="button" @click="selectedVal = 'gear'; selectedLabel = 'Alat Pribadi (Gear)'; open = false;"
                                         class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                                     <span>Alat Pribadi (Gear)</span>
                                     <span class="text-[10px] text-muted font-normal">Trekking/Pack</span>
                                 </button>
                                 <button type="button" @click="selectedVal = 'sleep'; selectedLabel = 'Tidur (Sleeping)'; open = false;"
                                         class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                                     <span>Tidur (Sleeping)</span>
                                     <span class="text-[10px] text-muted font-normal">Tenda/SB</span>
                                 </button>
                                 <button type="button" @click="selectedVal = 'lighting'; selectedLabel = 'Penerangan'; open = false;"
                                         class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                                     <span>Penerangan</span>
                                     <span class="text-[10px] text-muted font-normal">Headlamp</span>
                                 </button>
                                 <button type="button" @click="selectedVal = 'cook'; selectedLabel = 'Memasak'; open = false;"
                                         class="w-full text-left px-4 py-2 hover:bg-gray-50 text-body-strong font-medium flex items-center justify-between cursor-pointer">
                                     <span>Memasak</span>
                                     <span class="text-[10px] text-muted font-normal">Cooking Set</span>
                                 </button>
                             </div>
                         </div>

                         <div>
                             <label class="block text-[11px] font-bold text-muted uppercase mb-1">Harga Sewa / Trip (Rp)</label>
                             <input type="number" name="price" value="25000" min="0" required
                                 class="w-full text-xs rounded-xl border border-hairline bg-canvas p-3 text-ink focus:border-primary font-bold shadow-2xs">
                         </div>
                     </div>

                     <div>
                         <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-ink-heading pt-1">
                             <input type="checkbox" name="is_active" value="1" checked
                                 class="rounded border-hairline text-primary focus:ring-primary">
                             <span>Aktifkan untuk disewa pelanggan</span>
                         </label>
                     </div>

                     <div class="flex items-center justify-end gap-2 pt-3 border-t border-hairline">
                         <button type="button" @click="addModalOpen = false"
                             class="px-4 py-2 rounded-full border border-hairline text-xs font-semibold hover:bg-canvas">Batal</button>
                         <button type="submit"
                             class="px-5 py-2 rounded-full bg-primary text-white text-xs font-bold hover:bg-primary-hover shadow-xs">Simpan Alat</button>
                     </div>
                 </form>
             </div>
         </div>

    </div>
@endsection
