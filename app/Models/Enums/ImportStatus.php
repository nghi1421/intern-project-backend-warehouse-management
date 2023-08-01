<?php

namespace App\Models\Enums;

enum ImportStatus: int
{
    case Canceled = 0;

    case Initial = 1;

    case Checking = 2;

    case Completed = 3;

    public function label(): string|null
    {
        return  match ($this) {

            default => null,

            self::Canceled => __('Hủy'),

            self::Initial => __('Khởi tạo'),

            self::Checking => __('Đang kiểm tra'),

            self::Completed => __('Hoàn thành'),
        };
    }
}
