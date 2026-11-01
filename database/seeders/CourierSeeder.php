<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Courier::factory()->createMany([
            [
                'name' => 'Budiono Hadi Agung',
                'phone' => '081234567801',
                'level' => 2,
                'registered_at' => '2026-10-01',
            ],
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567802',
                'level' => 1,
                'registered_at' => '2026-09-15',
            ],
            [
                'name' => 'Agung Pratama',
                'phone' => '081234567803',
                'level' => 3,
                'registered_at' => '2026-10-20',
            ],
        ]);

        Courier::factory()->count(27)->create();
    }
}
