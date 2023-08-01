<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            [
                'id' => 1,
                'name' => 'Quản lí',
            ],
            [
                'id' => 2,
                'name' => 'Thủ kho',
            ],
            [
                'id' => 3,
                'name' => 'Nhân viên kho',
            ]
        ];

        foreach ($positions as $position) {

            Position::query()->create($position);
        }
    }
}
