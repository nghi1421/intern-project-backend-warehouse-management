<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class WarehouseBranchSeeder extends Seeder
{

    public function run(): void
    {
        $branches = [
            [
                'id' => 1,
                'name' => 'Cua hang Quan 9',
                'address' => '97 Man Thien, Quan 9, Tp Ho Chi Minh',
                'email' => 'cuahang1@gmail.com',
                'phone_number' => '0999888666',
                'opening' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Cua hang Quan Tan Binh',
                'address' => '12 Cong Hoa, Quan Tan Binh, Tp Ho Chi Minh',
                'email' => 'cuahang2@gmail.com',
                'phone_number' => '0999666333',
                'opening' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Cua hang Quan 2',
                'address' => '11 Mai Chi Tho, Quan 2, Tp Ho Chi Minh',
                'email' => 'cuahang3@gmail.com',
                'phone_number' => '0999888999',
                'opening' => 0,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::query()->create($branch);
        }
    }
}
