<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'id' => 1,
                'name' => 'manage-all-staff', //Manager
                'description' => 'Can implement CRUD all staffs'
            ],
            [
                'id' => 2,
                'name' => 'statistic',
                'description' => 'Statistic stock, customer, amount import... in date range'
            ],
            [
                'id' => 3,
                'name' => 'manage-category',
                'description' => 'Can implement CRUD category'
            ],
            [
                'id' => 4,
                'name' => 'manage-provider',
                'description' => 'Can implement CRUD provider'
            ],
            [
                'id' => 5,
                'name' => 'manage-conservation',
                'description' => 'Can implement CRUD conservation'
            ],
            [
                'id' => 6,
                'name' => 'manage-warehouse-branch',
                'description' => 'Can implement CRUD warehouse branch'
            ],
            [
                'id' => 7,
                'name' => 'read-stock',
                'description' => 'Can read stocks'
            ],
            [
                'id' => 8,
                'name' => 'read-import',
                'description' => 'Can read imports'
            ],
            [
                'id' => 9,
                'name' => 'read-export',
                'description' => 'Can read exports'
            ],
            [
                'id' => 10,
                'name' => 'manage-position',
                'description' => 'Can implement CRUD position'
            ],
            [
                'id' => 11,
                'name' => 'read-location',
                'description' => 'Can read location'
            ],
            [
                'id' => 12,
                'name' => 'change-permission',
                'description' => 'Can change permission for lower staffs'
            ],
            [
                'id' => 13,
                'name' => 'manage-branch-staff', //Stocker
                'description' => 'Can implement CRUD staffs of there branch'
            ],
            [
                'id' => 14,
                'name' => 'read-category',
                'description' => 'Can read category'
            ],
            [
                'id' => 15,
                'name' => 'read-conservation',
                'description' => 'Can read conservation'
            ],
            [
                'id' => 16,
                'name' => 'read-provider',
                'description' => 'Can read conservation'
            ],
            [
                'id' => 17,
                'name' => 'read-branch-stock',
                'description' => 'Can read stock of warehouse branch staff is working'
            ],
            [
                'id' => 18,
                'name' => 'read-branch-import',
                'description' => 'Can read import of warehouse branch staff is working'
            ],
            [
                'id' => 19,
                'name' => 'read-branch-export',
                'description' => 'Can read export of warehouse branch staff is working'
            ],
            [
                'id' => 20,
                'name' => 'export-report',
                'description' => 'Can export report of warehouse branch staff is working'
            ],
            [
                'id' => 21,
                'name' => 'manage-branch-import', //warehouse-staff
                'description' => 'Can CRUD import at warehouse branch user is working'
            ],
            [
                'id' => 22,
                'name' => 'manage-branch-export', //warehouse-staff
                'description' => 'Can CRUD export at warehouse branch user is working'
            ],
            [
                'id' => 23,
                'name' => 'manage-branch-stock', //warehouse-staff
                'description' => 'Can CRUD stock at warehouse branch user is working'
            ],
            [
                'id' => 24,
                'name' => 'read-warehouse-branch',
                'description' => 'Can read warehouse branches'
            ],
            [
                'id' => 25,
                'name' => 'read-position',
                'description' => 'Can read positions'
            ]
        ];

        foreach ($permissions as $permission) {

            Permission::query()->create($permission);
        }
    }
}