<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'role_id'  => 1,
                'username' => 'nguyenvananh123',
                'password' => '123123123',
            ],
            [
                'id' => 2,
                'role_id'  => 2,
                'username' => 'tranvanb123',
                'password' => '123123123',
            ],
            [
                'id' => 3,
                'role_id'  => 3,
                'username' => 'trandinhhuy123',
                'password' => '123123123',
            ],
            [
                'id' => 4,
                'role_id'  => 3,
                'username' => 'nguyenvietduc123',
                'password' => '123123123',
            ],
            [
                'id' => 5,
                'role_id'  => 3,
                'username' => 'nguyenviethai123',
                'password' => '123123123',
            ],
            [
                'id' => 6,
                'role_id'  => 2,
                'username' => 'levanluong123',
                'password' => '123123123',
            ],
            [
                'id' => 7,
                'role_id'  => 3,
                'username' => 'phanvankiet123',
                'password' => '123123123',
            ],
            [
                'id' => 8,
                'role_id'  => 3,
                'username' => 'nguyenleanhkiet123',
                'password' => '123123123',
            ],
            [
                'id' => 9,
                'role_id'  => 3,
                'username' => 'tranvanvien123',
                'password' => '123123123',
            ],
        ];

        foreach ($users as $user) {
            User::query()->create($user);
        }
    }
}
