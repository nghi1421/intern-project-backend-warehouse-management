<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            [
                'id' => 1,
                'name' => 'Nhà cung cấp số 1',
                'email' => 'nhacungcap1@gmail.com',
                'phone_number' => '0258369568',
                'address' => 'Man Thiện, quận 9, tp Hồ Chí Minh'
            ],
            [
                'id' => 2,
                'name' => 'Nhà cung cấp số 2',
                'email' => 'nhacungcap3@gmail.com',
                'phone_number' => '0333666555',
                'address' => 'Man Thiện, quận 9, tp Hồ Chí Minh'
            ],
            [
                'id' => 3,
                'name' => 'Nhà cung cấp số 3',
                'email' => 'nhacungcap3@gmail.com',
                'phone_number' => '0359682456',
                'address' => 'Man Thiện, quận 9, tp Hồ Chí Minh'
            ],
            [
                'id' => 4,
                'name' => 'Nhà cung cấp số 4',
                'email' => 'nhacungcap4@gmail.com',
                'phone_number' => '0999666524',
                'address' => 'Man Thiện, quận 9, tp Hồ Chí Minh'
            ],
            [
                'id' => 5,
                'name' => 'Nhà cung cấp số 5',
                'email' => 'nhacungcap5@gmail.com',
                'phone_number' => '0965485235',
                'address' => 'Man Thiện, quận 9, tp Hồ Chí Minh'
            ],
        ];

        foreach ($providers as $provider) {

            Provider::query()->create($provider);
        }
    }
}
