<!-- Footer -->
<footer id="contact" class="w-full bg-canvas-alt border-t border-gray-100 py-12 text-center text-muted">
    <div class="max-w-xl mx-auto px-4 flex flex-col items-center gap-4">

        <!-- Footer Brand -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-ink-heading text-lg group">
            <svg class="w-5 h-5 text-primary group-hover:scale-105 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
            </svg>
            <span>MiddleTrip</span>
        </a>

        <!-- Social / Legal Links -->
        <div class="flex flex-wrap justify-center items-center gap-6 text-xs text-muted font-medium">
            <a href="#" class="hover:text-primary transition">Instagram</a>
            <a href="https://wa.me/" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition">WhatsApp</a>
            <a href="mailto:info@middletrip.id" class="hover:text-primary transition">Email</a>
            <a href="#" class="hover:text-primary transition">Kebijakan Privasi</a>
            <a href="#" class="hover:text-primary transition">Syarat & Ketentuan</a>
        </div>

        <!-- Copyright -->
        <p class="text-[11px] text-muted-soft mt-2">
            © {{ date('Y') }} MiddleTrip Expedition Co. All rights reserved.
        </p>
    </div>
</footer>
