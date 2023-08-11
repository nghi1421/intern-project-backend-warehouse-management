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
                'name' => 'Nhan vien quan li',
                'actionIds' => [
                    1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 21, 22, 30, 28
                ]
            ],
            [
                'id' => 2,
                'name' => 'Thu kho',
                'actionIds' => [13, 14, 15, 16, 18, 19, 20, 23, 24, 25, 26, 27, 29]
            ],
            [
                'id' => 3,
                'name' => 'Nhan vien kho',
                'actionIds' => [17, 11, 14, 15, 16, 23]
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
