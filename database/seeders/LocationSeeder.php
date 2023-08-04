<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'id' => 1,
                'name' => 'Khu A, ke 1',
                'description' => 'Gan cua A',
                'warehouse_branch_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Khu A, ke 2',
                'description' => 'Gan cua A',
                'warehouse_branch_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Khu A, ke 3',
                'description' => 'Gan cua A',
                'warehouse_branch_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Khu A, ke 4',
                'description' => 'Gan cua A',
                'warehouse_branch_id' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Khu A, ke 5',
                'description' => 'Gan cua A',
                'warehouse_branch_id' => 1,
            ],
            [
                'id' => 6,
                'name' => 'Khu B, ke 1',
                'description' => 'Gan cua B',
                'warehouse_branch_id' => 1,
            ],
            [
                'id' => 7,
                'name' => 'Khu B, ke 2',
                'description' => 'Gan cua B',
                'warehouse_branch_id' => 1,
            ],
            [
                'id' => 8,
                'name' => 'Khu B, ke 3',
                'description' => 'Gan cua B',
                'warehouse_branch_id' => 1,
            ],
            [
                'id' => 9,
                'name' => 'Khu B, ke 4',
                'description' => 'Gan cua B',
                'warehouse_branch_id' => 1,
            ],
            [
                'id' => 10,
                'name' => 'Khu B, ke 5',
                'description' => 'Gan cua B',
                'warehouse_branch_id' => 1,
            ],
        ];

        foreach ($locations as $location) {
            Location::query()->create($location);
        }
    }
}