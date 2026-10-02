@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'badgeColor' => 'primary',
    'backUrl' => null,
    'backLabel' => 'Kembali',
])

<div
    class="bg-surface-card border border-hairline rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-xs relative overflow-hidden group">
    <!-- Subtle Ambient Glow -->
    <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 space-y-2">
        @if ($backUrl)
            <a href="{{ $backUrl }}"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted hover:text-primary transition-colors mb-1 group/back">
                <svg class="w-4 h-4 transition-transform group-hover/back:-translate-x-0.5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>{{ $backLabel }}</span>
            </a>
        @endif

        <div class="flex flex-wrap items-center gap-2.5">
            @if ($badge)
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold font-outfit uppercase tracking-wider bg-primary-subtle text-primary border border-primary/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    {{ $badge }}
                </span>
            @endif
        </div>

        <h1 class="text-2xl md:text-3xl font-extrabold font-outfit text-ink-heading tracking-tight leading-tight">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p class="text-xs md:text-sm text-body max-w-2xl leading-relaxed">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    @if (isset($actions) || $slot->isNotEmpty())
        <div class="flex flex-wrap items-center gap-3 relative z-10 shrink-0 w-full md:w-auto">
            {{ $actions ?? $slot }}
        </div>
    @endif
</div>
