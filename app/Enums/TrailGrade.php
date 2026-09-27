<?php

namespace App\Enums;

enum TrailGrade: string
{
    case GradeA = 'Grade A';
    case GradeB = 'Grade B';
    case GradeC = 'Grade C';

    public function label(): string
    {
        return match ($this) {
            self::GradeA => 'Grade A – Pemula',
            self::GradeB => 'Grade B – Menengah',
            self::GradeC => 'Grade C – Ahli',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::GradeA => 'Grade A',
            self::GradeB => 'Grade B',
            self::GradeC => 'Grade C',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::GradeA => 'bg-grade-a-bg text-grade-a-text',
            self::GradeB => 'bg-grade-b-bg text-grade-b-text',
            self::GradeC => 'bg-grade-c-bg text-grade-c-text',
        };
    }

    public function dotClass(): string
    {
        return match ($this) {
            self::GradeA => 'bg-grade-a-dot',
            self::GradeB => 'bg-grade-b-dot',
            self::GradeC => 'bg-grade-c-dot',
        };
    }

    public function textClass(): string
    {
        return match ($this) {
            self::GradeA => 'text-grade-a-text',
            self::GradeB => 'text-grade-b-text',
            self::GradeC => 'text-grade-c-text',
        };
    }
}
