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
                'name' => 'manager',
            ],
            [
                'name' => 'stocker',
            ],
            [
                'name' => 'warehouse-staff',
            ]
        ];

        foreach ($positions as $position) {

            Position::query()->create($position);
        }
    }
}
