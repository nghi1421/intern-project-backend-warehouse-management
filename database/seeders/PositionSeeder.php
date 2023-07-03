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
                'actionIds' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
            ],
            [
                'name' => 'stocker',
                'actionIds' => [13, 14, 15, 16, 17, 18, 19, 20]
            ],
            [
                'name' => 'warehouse-staff',
                'actionIds' => [21, 22, 17, 11, 14, 15, 16,]
            ]
        ];

        foreach ($positions as $position) {

            Position::query()
                ->create(['name' => $position['name']])
                ->actions()
                ->attach($position['actionIds']);
        }
    }
}
