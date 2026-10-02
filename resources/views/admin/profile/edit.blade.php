@extends('layouts.admin')

@section('title', 'Pengaturan Profil Admin')
@section('header_title', 'Pengaturan Akun & Profil')
@section('header_subtitle', 'Kelola informasi kredensial dan preferensi akun administrator')

@section('content')
<div class="space-y-6">

    <!-- Page Header Component -->
    <x-admin.page-header 
        title="Profil & Keamanan Akun"
        subtitle="Perbarui identitas, kontak operator, serta kata sandi akun administrator MiddleTrip"
    >
        <x-slot:actions>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold font-outfit bg-primary-subtle text-primary border border-primary/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Akun Administrator Resmi</span>
            </span>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Main 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column: Informasi Akun (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 sm:p-7 shadow-xs">
                <div class="flex items-center gap-3 border-b border-hairline pb-4 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-primary-subtle text-primary flex items-center justify-center font-bold text-base shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold font-outfit text-ink-heading">Informasi Profil Administrator</h3>
                        <p class="text-xs text-muted">Perbarui data identitas diri dan email operasional Anda</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-bold font-outfit text-ink-heading mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full text-xs rounded-xl border border-hairline bg-surface-card px-3.5 py-2.5 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-2xs transition"
                               placeholder="Nama Lengkap Operator">
                        @error('name')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold font-outfit text-ink-heading mb-1.5">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full text-xs rounded-xl border border-hairline bg-surface-card px-3.5 py-2.5 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-2xs transition"
                               placeholder="admin@middletrip.id">
                        @error('email')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Info -->
                    <div>
                        <label class="block text-xs font-bold font-outfit text-ink-heading mb-1.5">
                            Hak Akses & Peran
                        </label>
                        <input type="text" value="Super Administrator (Full Access)" readonly
                               class="w-full text-xs rounded-xl border border-hairline bg-surface-subtle px-3.5 py-2.5 text-ink-muted font-bold cursor-not-allowed shadow-2xs">
                    </div>

                    <div class="pt-4 border-t border-hairline flex justify-end">
                        <button type="submit" 
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl sm:rounded-full bg-primary hover:bg-primary-hover text-white text-xs font-bold font-outfit shadow-sm transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Ganti Password & Info Sesi (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Card Ganti Kata Sandi -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 sm:p-7 shadow-xs">
                <div class="flex items-center gap-3 border-b border-hairline pb-4 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-base shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold font-outfit text-ink-heading">Ubah Kata Sandi</h3>
                        <p class="text-xs text-muted">Amankan akun dengan kata sandi yang kuat</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Password Saat Ini -->
                    <div>
                        <label for="current_password" class="block text-xs font-bold font-outfit text-ink-heading mb-1.5">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="current_password" id="current_password" required
                               class="w-full text-xs rounded-xl border border-hairline bg-surface-card px-3.5 py-2.5 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-2xs transition"
                               placeholder="••••••••">
                        @error('current_password')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label for="password" class="block text-xs font-bold font-outfit text-ink-heading mb-1.5">
                            Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required minlength="8"
                               class="w-full text-xs rounded-xl border border-hairline bg-surface-card px-3.5 py-2.5 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-2xs transition"
                               placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold font-outfit text-ink-heading mb-1.5">
                            Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                               class="w-full text-xs rounded-xl border border-hairline bg-surface-card px-3.5 py-2.5 text-ink focus:border-primary focus:ring-1 focus:ring-primary shadow-2xs transition"
                               placeholder="Ulangi kata sandi baru">
                        @error('password_confirmation')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-hairline flex justify-end">
                        <button type="submit" 
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl sm:rounded-full bg-surface-forest hover:bg-surface-forest-card text-white text-xs font-bold font-outfit shadow-sm transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Ringkasan Status Akun -->
            <div class="bg-surface-card border border-hairline rounded-3xl p-6 shadow-xs space-y-3">
                <h4 class="text-xs font-bold font-outfit text-ink-heading uppercase tracking-wider">Status Hak Akses</h4>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-muted">Peran Akun</span>
                        <span class="font-bold text-primary font-outfit">Super Administrator</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-muted">Terdaftar Sejak</span>
                        <span class="font-medium text-ink">{{ $user->created_at->format('d F Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-muted">Status Keamanan</span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Aktif & Terverifikasi</span>
                        </span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
