<?php

namespace App\Models\Enums;

enum Gender: int
{
    case Male = 0;

    case Female = 1;

    case Other = 2;

    public function label(): string|null
    {
        return  match ($this) {

            default => null,

            self::Male => __('Male'),

            self::Female => __('Female'),

            self::Other => __('Other'),
        };
    }
}
