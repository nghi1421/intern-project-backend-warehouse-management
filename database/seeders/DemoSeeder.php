<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        $this->call(PositionSeeder::class);

        $this->call(WarehouseBranchSeeder::class);

        $this->call(BranchSeeder::class);

        $this->call(RoleSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(StaffSeeder::class);

        $this->call(PrincipleSeeder::class);

        $this->call(CategorySeeder::class);

        $this->call(ProviderSeeder::class);
    }
}
