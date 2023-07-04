<?php

namespace Database\Seeders;

use App\Models\WarehouseBranch;
use Illuminate\Database\Seeder;

class WarehouseBranchSeeder extends Seeder
{

    public function run(): void
    {
        $warehouses = [
            [
                'id' => 1,
                'name' => 'Nha kho Quan 9',
                'address' => '97 Man Thien, Quan 9, Tp Ho Chi Minh',
                'email' => 'nhakho1@gmail.com',
                'phone_number' => '0999888666',
                'opening' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Nha kho Quan Tan Binh',
                'address' => '12 Cong Hoa, Quan Tan Binh, Tp Ho Chi Minh',
                'email' => 'nhakho2@gmail.com',
                'phone_number' => '0999666333',
                'opening' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Nha kho Quan 2',
                'address' => '11 Mai Chi Tho, Quan 2, Tp Ho Chi Minh',
                'email' => 'nhakho3@gmail.com',
                'phone_number' => '0999888999',
                'opening' => 0,
            ],
        ];

        foreach ($warehouses as $warehouse) {
            WarehouseBranch::query()->create($warehouse);
        }
    }
}
