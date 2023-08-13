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
                'name' => 'read-role',
                'description' => 'Can read role'
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
                'name' => 'manage-import',
                'description' => 'Can implement CRUD import'
            ],
            [
                'id' => 6,
                'name' => 'manage-export',
                'description' => 'Can implement CRUD export'
            ],
            [
                'id' => 7,
                'name' => 'manage-account',
                'description' => 'Can implement CRUD account'
            ],
            [
                'id' => 8,
                'name' => 'manage-warehouse-branch',
                'description' => 'Can implement CRUD warehouse branch'
            ],
            [
                'id' => 9,
                'name' => 'manage-stock',
                'description' => 'Can implement CRUD stock'
            ],
            [
                'id' => 10,
                'name' => 'manage-location',
                'description' => 'Can implement CRUD location'
            ],
            [
                'id' => 11,
                'name' => 'manage-position',
                'description' => 'Can implement CRUD position'
            ],
            [
                'id' => 12,
                'name' => 'change-permission-user',
                'description' => 'Can change permission for lower staffs'
            ],
            [
                'id' => 13,
                'name' => 'cancel-import',
                'description' => 'Cacel import'
            ],
            [
                'id' => 14,
                'name' => 'manage-branch-staff', //Stocker
                'description' => 'Can implement CRUD staffs of there branch'
            ],
            [
                'id' => 15,
                'name' => 'read-category',
                'description' => 'Can read category'
            ],
            [
                'id' => 16,
                'name' => 'read-provider',
                'description' => 'Can read conservation'
            ],
            [
                'id' => 17,
                'name' => 'manage-branch-stock',
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
            // [
            //     'id' => 20,
            //     'name' => 'export-report',
            //     'description' => 'Can export report of warehouse branch staff is working'
            // ],
            // [
            //     'id' => 21,
            //     'name' => 'manage-import',
            //     'description' => 'Can CRUD import at warehouse branch user is working'
            // ],
            // [
            //     'id' => 22,
            //     'name' => 'manage-export',
            //     'description' => 'Can CRUD export at warehouse branch user is working'
            // ],

            [
                'id' => 20,
                'name' => 'read-position',
                'description' => 'Can read positions'
            ],
            [
                'id' => 21,
                'name' => 'update-import-status',
                'description' => 'Can update status of import'
            ],
            [
                'id' => 22,
                'name' => 'update-export-status',
                'description' => 'Can update status of export'
            ],
            [
                'id' => 23,
                'name' => 'manage-branch-location',
                'description' => 'Can read branch location'
            ],
            [
                'id' => 24,
                'name' => 'read-warehouse-branch',
                'description' => 'Can read branch location'
            ],
            [
                'id' => 25,
                'name' => 'read-permission',
                'description' => 'Can read branch location'
            ],
            [
                'id' => 26,
                'name' => 'read-branch-export',
                'description' => 'Can read category'
            ],
            [
                'id' => 27,
                'name' => 'read-branch-location',
                'description' => 'Can read category'
            ],
        ];

        foreach ($permissions as $permission) {

            Permission::query()->create($permission);
        }
    }
}
