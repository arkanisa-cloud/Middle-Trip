@props([
    'mountain',
])

@php
    $grade = $mountain->default_grade;
    $typeVal = ($mountain->has_open_trip && $mountain->has_private_trip) 
        ? 'both' 
        : ($mountain->has_private_trip ? 'private' : 'open');
@endphp

<article data-grade="{{ $grade?->value }}" data-type="{{ $typeVal }}" data-mountain="{{ strtolower($mountain->name) }}" data-title="{{ strtolower($mountain->name) }}" class="trip-card bg-surface-card rounded-2xl overflow-hidden border border-hairline-soft shadow-sm hover:shadow-md transition duration-300 flex flex-col group">
    <div class="relative h-56 w-full overflow-hidden bg-gray-200">
        <img 
            src="{{ $mountain->cover_image }}" 
            alt="{{ $mountain->name }}" 
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            loading="lazy"
            decoding="async"
        />
        <div class="absolute top-3 left-3 flex items-center gap-2">
            <x-grade-badge :grade="$grade" size="md" />
            @if(!$mountain->has_open_trip && $mountain->has_private_trip)
                <span class="inline-block bg-black/50 backdrop-blur-sm text-white text-[10px] font-semibold px-2.5 py-0.5 rounded-full">
                    Private Only
                </span>
            @endif
        </div>
    </div>

    <div class="p-5 flex-1 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-ink-heading tracking-tight group-hover:text-primary transition">
                <a href="{{ route('ekspedisi.show', $mountain->slug) }}">
                    {{ $mountain->name }}
                </a>
            </h3>
            <p class="flex items-center gap-1.5 text-xs text-muted mt-1 font-medium">
                <svg class="w-3.5 h-3.5 text-muted-soft" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                </svg>
                <span class="font-liberation tracking-tight">{{ $mountain->formatted_elevation }}</span>
            </p>
            <p class="text-xs text-body mt-2 line-clamp-2 leading-relaxed">
                {{ $mountain->description }}
            </p>
        </div>

        <div class="mt-6 pt-3 flex items-end justify-between border-t border-hairline-soft">
            <div>
                <span class="text-[11px] text-muted-soft block font-medium">Mulai dari</span>
                <p class="text-sm md:text-base font-bold text-ink-heading">
                    {{ $mountain->formatted_price }} <span class="text-xs font-normal text-muted font-sans">/ pax</span>
                </p>
            </div>

            <a 
                href="{{ route('ekspedisi.show', $mountain->slug) }}"
                class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary-hover active:bg-primary-active text-white text-xs font-semibold px-4 py-2 rounded-full transition shadow-sm">
                <span>Pilih Trip</span>
                <span class="text-sm">→</span>
            </a>
        </div>
    </div>
</article>
