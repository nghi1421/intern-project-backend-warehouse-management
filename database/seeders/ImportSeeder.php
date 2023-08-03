<?php

namespace Database\Seeders;

use App\Models\Import;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ImportSeeder extends Seeder
{
    public function run(): void
    {
        $import = Import::query()->create([
            'staff_id' => 1,
            'status' => 1,
            'provider_id' => 1,
            'warehouse_branch_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $importDetail = [
            '1' => [
                'quantity' => 10,
                'unit_price' => 100000,
            ],
            '2' => [
                'quantity' => 20,
                'unit_price' => 120000,
            ]
        ];

        $import->categories()->sync($importDetail);
    }
}
