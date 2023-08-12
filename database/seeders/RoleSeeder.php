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
                    1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13
                ]
            ],
            [
                'id' => 2,
                'name' => 'Thu kho',
                'actionIds' => [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24]
            ],
            [
                'id' => 3,
                'name' => 'Nhan vien kho',
                'actionIds' => [17]
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