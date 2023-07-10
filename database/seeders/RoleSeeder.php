<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{

    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'manager',
                'actionIds' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
            ],
            [
                'id' => 2,
                'name' => 'stocker',
                'actionIds' => [13, 14, 15, 16, 17, 18, 19, 20]
            ],
            [
                'id' => 3,
                'name' => 'warehouse-staff',
                'actionIds' => [21, 22, 17, 11, 14, 15, 16,]
            ]
        ];

        foreach ($roles as $role) {

            Role::query()
                ->create(['name' => $role['name']])
                ->permissions()
                ->attach($role['actionIds']);
        }
    }
}
