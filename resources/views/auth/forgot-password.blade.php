<x-guest-layout>
    <div class="mb-5 text-center">
        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-primary-subtle text-primary mb-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </div>
        <h1 class="text-xl font-extrabold text-ink-heading tracking-tight mb-1">Lupa Kata Sandi?</h1>
        <p class="text-xs text-muted leading-relaxed">
            Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan reset kata sandi ke kotak masuk Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[11px] font-bold text-ink-heading uppercase tracking-wider mb-2">
                Alamat Email
            </label>
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" placeholder="nama@email.com" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-primary hover:bg-primary-hover active:bg-primary-active text-white font-bold text-xs sm:text-sm py-3 px-6 rounded-full shadow-2xs hover:shadow transition-all duration-200 cursor-pointer flex items-center justify-center gap-2">
                <span>Kirim Link Reset Kata Sandi</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>

        <div class="pt-2 text-center">
            <a href="{{ route('login') }}" class="text-xs font-bold text-muted hover:text-primary transition-colors">
                ← Kembali ke Halaman Masuk
            </a>
        </div>
    </form>
</x-guest-layout>
