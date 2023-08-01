<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Sữa tươi',
                'unit' => 'thùng',
                'description' => 'Sữa bò tươi'
            ],
            [
                'id' => 2,
                'name' => 'Sting đỏ',
                'unit' => 'thùng',
                'description' => 'Sting đỏ thùng 24 chai'
            ],
            [
                'id' => 3,
                'name' => 'Nước suối',
                'unit' => 'lốc',
                'description' => 'Nước suối lốc 6 chai'
            ],
            [
                'id' => 4,
                'name' => 'Sting vàng',
                'unit' => 'thùng',
                'description' => 'Sting đỏ thùng 24 chai'
            ],
            [
                'id' => 5,
                'name' => 'Sữa đặc',
                'unit' => 'thùng',
                'description' => 'thùng 12 long',
            ],
            [
                'id' => 6,
                'name' => 'Mì hảo hảo',
                'unit' => 'thùng',
                'description' => 'thùng 30 gói',
            ],
            [
                'id' => 7,
                'name' => 'Mì vắt',
                'unit' => 'thùng',
                'description' => 'Thùng 8 gói lớn',
            ],
        ];

        foreach ($categories as $category) {

            Category::query()->create($category);
        }
    }
}
