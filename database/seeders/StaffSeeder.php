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
                'address' => 'Man Thien',
                'gender' => 1,
                'position_id' => 1,
                'warehouse_branch_id' => 1,
                'dob' => '2001-04-01',
                'working' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Le Thi Thu Thao',
                'phone_number' => '0111222333',
                'avatar' => '',
                'address' => 'Vung Tau',
                'gender' => 0,
                'position_id' => 2,
                'warehouse_branch_id' => 1,
                'dob' => '2002-12-01',
                'working' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Tran Dinh Huy',
                'phone_number' => '011447754',
                'avatar' => '',
                'address' => 'Thu Dau Mot',
                'gender' => 1,
                'position_id' => 3,
                'warehouse_branch_id' => 1,
                'dob' => '2001-01-01',
                'working' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Nguyen Viet Duc',
                'phone_number' => '0124578124',
                'avatar' => '',
                'address' => 'Thu Duc, Tp Ho Chi Minh',
                'gender' => 1,
                'position_id' => 3,
                'warehouse_branch_id' => 1,
                'dob' => '2003-04-01',
                'working' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Nguyen Viet Hai',
                'phone_number' => '0114455668',
                'avatar' => '',
                'address' => 'Quan 1',
                'gender' => 1,
                'position_id' => 3,
                'warehouse_branch_id' => 1,
                'dob' => '2003-04-01',
                'working' => 0,
            ],
            [
                'id' => 6,
                'name' => 'Le Van Luong',
                'phone_number' => '0999888777',
                'avatar' => '',
                'address' => 'Quan 9',
                'gender' => 1,
                'position_id' => 2,
                'warehouse_branch_id' => 2,
                'dob' => '2002-12-11',
                'working' => 1,
            ],
            [
                'id' => 7,
                'name' => 'Phan Van Kiet',
                'phone_number' => '0159753684',
                'avatar' => '',
                'address' => 'Quan 12',
                'gender' => 1,
                'position_id' => 3,
                'warehouse_branch_id' => 2,
                'dob' => '2001-10-01',
                'working' => 1,
            ],
            [
                'id' => 8,
                'name' => 'Nguyen Le Anh Kiet',
                'phone_number' => '0111444558',
                'avatar' => '',
                'address' => 'Quan 2',
                'gender' => 1,
                'position_id' => 3,
                'warehouse_branch_id' => 2,
                'dob' => '2003-04-01',
                'working' => 1,
            ],
            [
                'id' => 9,
                'name' => 'Tran Van Vien',
                'phone_number' => '0987111111',
                'avatar' => '',
                'address' => 'Quan 2',
                'gender' => 1,
                'position_id' => 3,
                'warehouse_branch_id' => 2,
                'dob' => '2004-04-01',
                'working' => 0,
            ],
        ];

        foreach ($staffs as $index => $staff) {
            $newStaff = Staff::query()->create($staff);

            $newStaff->user()->associate($index + 1);
        }
    }
}
