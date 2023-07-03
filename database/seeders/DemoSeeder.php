<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ActionsSeeder::class);

        $this->call(PositionSeeder::class);
    }
}
