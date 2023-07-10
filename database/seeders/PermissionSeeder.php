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
                'name' => 'manage_all_user', //Manager
                'description' => 'Can implement CRUD all users'
            ],
            [
                'id' => 2,
                'name' => 'statistic',
                'description' => 'Statistic stock, customer, amount import... in date range'
            ],
            [
                'id' => 3,
                'name' => 'manage_category',
                'description' => 'Can implement CRUD category'
            ],
            [
                'id' => 4,
                'name' => 'manage_provider',
                'description' => 'Can implement CRUD provider'
            ],
            [
                'id' => 5,
                'name' => 'manage_conservation',
                'description' => 'Can implement CRUD conservation'
            ],
            [
                'id' => 6,
                'name' => 'manage_warehouse_branch',
                'description' => 'Can implement CRUD warehouse branch'
            ],
            [
                'id' => 7,
                'name' => 'read_stock',
                'description' => 'Can read stocks'
            ],
            [
                'id' => 8,
                'name' => 'read_import',
                'description' => 'Can read imports'
            ],
            [
                'id' => 9,
                'name' => 'read_export',
                'description' => 'Can read exports'
            ],
            [
                'id' => 10,
                'name' => 'manage_position',
                'description' => 'Can implement CRUD position'
            ],
            [
                'id' => 11,
                'name' => 'read_location',
                'description' => 'Can read location'
            ],
            [
                'id' => 12,
                'name' => 'change_permission',
                'description' => 'Can change permission for lower users'
            ],
            [
                'id' => 13,
                'name' => 'manage_branch_user', //Stocker
                'description' => 'Can implement CRUD users of there branch'
            ],
            [
                'id' => 14,
                'name' => 'read_category',
                'description' => 'Can read category'
            ],
            [
                'id' => 15,
                'name' => 'read_conservation',
                'description' => 'Can read conservation'
            ],
            [
                'id' => 16,
                'name' => 'read_provider',
                'description' => 'Can read conservation'
            ],
            [
                'id' => 17,
                'name' => 'read_branch_stock',
                'description' => 'Can read stock of warehouse branch user is working'
            ],
            [
                'id' => 18,
                'name' => 'read_branch_import',
                'description' => 'Can read import of warehouse branch user is working'
            ],
            [
                'id' => 19,
                'name' => 'read_branch_export',
                'description' => 'Can read export of warehouse branch user is working'
            ],
            [
                'id' => 20,
                'name' => 'export_report',
                'description' => 'Can export report of warehouse branch user is working'
            ],
            [
                'id' => 21,
                'name' => 'manage_branch_import', //warehouse-staff
                'description' => 'Can CRUD import at warehouse branch user is working'
            ],
            [
                'id' => 22,
                'name' => 'manage_branch_export', //warehouse-staff
                'description' => 'Can CRUD export at warehouse branch user is working'
            ],
            [
                'id' => 23,
                'name' => 'manage_branch_stock', //warehouse-staff
                'description' => 'Can CRUD stock at warehouse branch user is working'
            ],
        ];

        foreach ($permissions as $permission) {

            Permission::query()->create($permission);
        }
    }
}
