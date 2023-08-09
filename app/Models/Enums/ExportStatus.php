<?php

namespace App\Models\Enums;

enum ExportStatus: int
{
    case NotComplete = 0;

    case Initial = 1;

    case Packing = 2;

    case Completed = 3;

    public function label(): string|null
    {
        return  match ($this) {

            default => null,

            self::NotComplete => __('Không hoàn thành'),

            self::Initial => __('Khởi tạo'),

            self::Packing => __('Đang đóng gói'),

            self::Completed => __('Hoàn thành'),
        };
    }
}
