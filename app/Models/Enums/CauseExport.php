<?php

namespace App\Models\Enums;

enum CauseExport: int
{
    case Normal = 1;

    case Broken = 2;

    case OutDate = 3;

    case Other = 4;

    public function label(): string|null
    {
        return  match ($this) {

            default => null,

            self::Normal => __('Normal'),

            self::Broken => __('Broken'),

            self::OutDate => __('Out Date'),

            self::Other => __('Other'),
        };
    }
}
