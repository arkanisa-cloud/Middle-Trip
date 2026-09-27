@props([
    'grade' => null,
    'size' => 'sm',
])

@php
    $gradeEnum = $grade instanceof \App\Enums\TrailGrade 
        ? $grade 
        : ($grade ? \App\Enums\TrailGrade::tryFrom($grade) : null);

    $badgeClasses = $gradeEnum?->badgeClasses() ?? 'bg-grade-a-bg text-grade-a-text';
    $dotClass = $gradeEnum?->dotClass() ?? 'bg-grade-a-dot';
    $label = $gradeEnum?->label() ?? ($grade ?? 'Grade A – Pemula');

    $sizeClasses = match($size) {
        'xs' => 'text-[9px] px-2 py-0.5',
        'md' => 'text-[11px] px-3 py-1 font-bold',
        default => 'text-[10px] px-2.5 py-0.5 font-bold',
    };
@endphp

<span class="{{ $badgeClasses }} {{ $sizeClasses }} rounded-full flex items-center gap-1 shadow-sm whitespace-nowrap">
    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} shrink-0"></span>
    <span>{{ $label }}</span>
</span>
