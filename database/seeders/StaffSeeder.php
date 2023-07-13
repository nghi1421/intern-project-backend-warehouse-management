<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffs = [
            [
                'id' => 1,
                'name' => 'Nguyen Van Anh',
                'phone_number' => '0123123123',
                'avatar' => '',
                'user_id' => 1,
                'address' => 'Man Thien',
                'gender' => 1,
                'position_id' => 1,
                'dob' => '2001-04-01',
                'working' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Le Thi Thu Thao',
                'phone_number' => '0111222333',
                'avatar' => '',
                'user_id' => 2,
                'address' => 'Vung Tau',
                'gender' => 0,
                'position_id' => 2,
                'dob' => '2002-12-01',
                'working' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Tran Dinh Huy',
                'phone_number' => '011447754',
                'avatar' => '',
                'user_id' => 3,
                'address' => 'Thu Dau Mot',
                'gender' => 1,
                'position_id' => 3,
                'dob' => '2001-01-01',
                'working' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Nguyen Viet Duc',
                'phone_number' => '0124578124',
                'avatar' => '',
                'user_id' => 4,
                'address' => 'Thu Duc, Tp Ho Chi Minh',
                'gender' => 1,
                'position_id' => 3,
                'dob' => '2003-04-01',
                'working' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Nguyen Viet Hai',
                'phone_number' => '0114455668',
                'avatar' => '',
                'user_id' => 5,
                'address' => 'Quan 1',
                'gender' => 1,
                'position_id' => 3,
                'dob' => '2003-04-01',
                'working' => 0,
            ],
            [
                'id' => 6,
                'name' => 'Le Van Luong',
                'phone_number' => '0999888777',
                'avatar' => '',
                'user_id' => 6,
                'address' => 'Quan 9',
                'gender' => 1,
                'position_id' => 2,
                'dob' => '2002-12-11',
                'working' => 1,
            ],
            [
                'id' => 7,
                'name' => 'Phan Van Kiet',
                'phone_number' => '0159753684',
                'avatar' => '',
                'user_id' => 7,
                'address' => 'Quan 12',
                'gender' => 1,
                'position_id' => 3,
                'dob' => '2001-10-01',
                'working' => 1,
            ],
            [
                'id' => 8,
                'name' => 'Nguyen Le Anh Kiet',
                'phone_number' => '0111444558',
                'avatar' => '',
                'user_id' => 8,
                'address' => 'Quan 2',
                'gender' => 1,
                'position_id' => 3,
                'dob' => '2003-04-01',
                'working' => 1,
            ],
            [
                'id' => 9,
                'name' => 'Tran Van Vien',
                'phone_number' => '0987111111',
                'avatar' => '',
                'user_id' => 9,
                'address' => 'Quan 2',
                'gender' => 1,
                'position_id' => 3,
                'dob' => '2004-04-01',
                'working' => 0,
            ],
        ];

        foreach ($staffs as $staff) {
            Staff::query()->create($staff);
        }
    }
}