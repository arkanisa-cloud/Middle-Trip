<x-guest-layout>
    <div class="mb-5 text-center">
        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-subtle text-primary mb-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0110 0v4" />
            </svg>
        </div>
        <h1 class="text-xl font-extrabold text-ink-heading tracking-tight mb-1">Perbarui Kata Sandi</h1>
        <p class="text-xs text-muted leading-relaxed">
            Buat kata sandi baru untuk akun pendaki MiddleTrip Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[11px] font-bold text-ink-heading uppercase tracking-wider mb-2">
                Alamat Email
            </label>
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-[11px] font-bold text-ink-heading uppercase tracking-wider mb-2">
                Kata Sandi Baru
            </label>
            <x-text-input id="password" class="block w-full" type="password" name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-[11px] font-bold text-ink-heading uppercase tracking-wider mb-2">
                Konfirmasi Kata Sandi Baru
            </label>
            <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-primary hover:bg-primary-hover active:bg-primary-active text-white font-bold text-xs sm:text-sm py-3 px-6 rounded-full shadow-2xs hover:shadow transition-all duration-200 cursor-pointer flex items-center justify-center gap-2">
                <span>Simpan Kata Sandi Baru</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</x-guest-layout>
