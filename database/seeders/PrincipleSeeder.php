<?php

namespace Database\Seeders;

use App\Models\Principle;
use Illuminate\Database\Seeder;

class PrincipleSeeder extends Seeder
{
    public function run(): void
    {
        $principles = [
            [
                'id' => 1,
                'name' => 'Cách mặt đất',
                'description' => 'Để thùng hàng ở kệ hàng cách mặt đất'
            ],
            [
                'id' => 2,
                'name' => 'Thông thoáng',
                'description' => 'Để thùng hàng ở gần lối đi chính',
            ],
            [
                'id' => 3,
                'name' => 'Tránh ánh nắng',
                'description' => 'Không để hàng hóa gần cửa sổ có ánh nắng chiếu trực tiếp',
            ],
            [
                'id' => 4,
                'name' => 'Bảo quản mát',
                'description' => 'Bảo quản nhiệt độ 20-30 độ',
            ],
            [
                'id' => 5,
                'name' => 'Bảo quản lạnh',
                'description' => 'Bảo quản bảo quản nhiệt độ dưới 10 độ',
            ],
            [
                'id' => 6,
                'name' => 'Không xếp chồng',
                'description' => 'Không xếp thùng khác khác chồng lên',
            ],
            [
                'id' => 7,
                'name' => 'Không kéo lê',
                'description' => 'Không được kéo lê thùng thùng',
            ],
        ];

        foreach ($principles as $principle) {

            Principle::query()->create($principle);
        }
    }
}