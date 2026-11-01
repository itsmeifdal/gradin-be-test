<?php

namespace Tests\Feature;

use App\Models\Courier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_is_paginated_and_sorted_by_name_by_default(): void
    {
        Courier::factory()->create(['name' => 'Zainal Courier']);
        Courier::factory()->create(['name' => 'Andi Courier']);

        $response = $this->getJson('/api/couriers?per_page=1');

        $response
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Andi Courier')
            ->assertJsonPath('per_page', 1)
            ->assertJsonPath('total', 2);
    }

    public function test_index_can_sort_by_latest_registration_date(): void
    {
        Courier::factory()->create([
            'name' => 'Older Courier',
            'registered_at' => '2026-01-01',
        ]);
        Courier::factory()->create([
            'name' => 'Newer Courier',
            'registered_at' => '2026-10-01',
        ]);

        $response = $this->getJson('/api/couriers?sort=registered_at');

        $response
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Newer Courier')
            ->assertJsonPath('data.1.name', 'Older Courier');
    }

    public function test_index_can_search_multiple_name_terms_and_filter_levels(): void
    {
        Courier::factory()->create(['name' => 'Budiono Hadi Agung', 'level' => 2]);
        Courier::factory()->create(['name' => 'Budi Santoso', 'level' => 1]);
        Courier::factory()->create(['name' => 'Agung Pratama', 'level' => 3]);

        $searchResponse = $this->getJson('/api/couriers?search=budi%20agung');

        $searchResponse
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Budiono Hadi Agung');

        $levelResponse = $this->getJson('/api/couriers?level=2,3');

        $levelResponse
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonMissing(['name' => 'Budi Santoso']);
    }

    public function test_store_show_update_and_destroy_courier(): void
    {
        $createResponse = $this->postJson('/api/couriers', [
            'name' => 'Budiono Hadi Agung',
            'phone' => '081234567890',
            'level' => 2,
            'registered_at' => '2026-11-01',
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.name', 'Budiono Hadi Agung');

        $courierId = $createResponse->json('data.id');

        $this->assertDatabaseHas('couriers', [
            'id' => $courierId,
            'phone' => '081234567890',
        ]);

        $this->getJson("/api/couriers/{$courierId}")
            ->assertOk()
            ->assertJsonPath('data.level', 2);

        $this->putJson("/api/couriers/{$courierId}", [
            'name' => 'Budiono Agung',
            'phone' => '081234567890',
            'level' => 3,
            'registered_at' => '2026-11-01',
        ])->assertOk()
            ->assertJsonPath('data.level', 3);

        $this->assertDatabaseHas('couriers', [
            'id' => $courierId,
            'name' => 'Budiono Agung',
            'level' => 3,
        ]);

        $this->deleteJson("/api/couriers/{$courierId}")->assertNoContent();

        $this->assertDatabaseMissing('couriers', ['id' => $courierId]);
        $this->getJson("/api/couriers/{$courierId}")->assertNotFound();
    }

    public function test_store_rejects_incomplete_or_invalid_input(): void
    {
        $this->postJson('/api/couriers', [
            'name' => '',
            'level' => 6,
            'registered_at' => '01-11-2026',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'phone', 'level', 'registered_at']);
    }

    public function test_update_rejects_duplicate_phone_number(): void
    {
        $firstCourier = Courier::factory()->create(['phone' => '081111111111']);
        $secondCourier = Courier::factory()->create(['phone' => '082222222222']);

        $this->putJson("/api/couriers/{$secondCourier->id}", [
            'name' => $secondCourier->name,
            'phone' => $firstCourier->phone,
            'level' => $secondCourier->level,
            'registered_at' => $secondCourier->registered_at->format('Y-m-d'),
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('phone');
    }
}
