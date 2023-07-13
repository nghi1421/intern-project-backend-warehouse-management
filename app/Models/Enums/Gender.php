<?php

namespace App\Models\Enums;

enum Gender: int
{
    case Female = 0;

    case Male = 1;

    case Other = 2;

    public function label(): string|null
    {
        return  match ($this) {

            default => null,

            self::Female => __('Female'),

            self::Male => __('Male'),

            self::Other => __('Other'),
        };
    }
}
