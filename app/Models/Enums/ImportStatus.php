<?php

namespace App\Models\Enums;

enum ImportStatus: int
{
    case Canceled = 0;

    case InProgress = 1;

    case Completed = 2;

    public function label(): string|null
    {
        return  match ($this) {

            default => null,

            self::Canceled => __('Cancel'),

            self::InProgress => __('In Progress'),

            self::Completed => __('Completed'),
        };
    }
}
