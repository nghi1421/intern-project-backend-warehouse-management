<?php

namespace App\Models\Enums;

enum WorkingStatus: int
{
    case Working = 1;

    case LeaveWork = 0;

    public function label(): string|null
    {
        return  match ($this) {

            default => null,

            self::LeaveWork => __('Leave work'),

            self::Working => __('Working'),
        };
    }
}
