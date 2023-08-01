<?php

namespace App\Models\Enums;

enum WorkingStatus: int
{
    case Initial = 1;

    case Packing = 2;

    case Completed = 3;

    public function label(): string|null
    {
        return  match ($this) {

            default => null,

            self::Initial => __('Khởi tạo'),

            self::Packing => __('Đang đóng gói'),

            self::Completed => __('Hoàn thành'),
        };
    }
}
