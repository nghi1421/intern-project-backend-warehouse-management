<?php

namespace App\Models\Enums;

enum WorkingStatus: int
{
    case Working = 0;

    case LeaveWork = 1;

    public function label(): string|null
    {
        return  match ($this) {

            default => null,

            self::Working => __('Working'),

            self::LeaveWork => __('Leave work'),
        };
    }
}
