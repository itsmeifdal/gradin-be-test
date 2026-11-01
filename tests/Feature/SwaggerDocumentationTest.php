<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SwaggerDocumentationTest extends TestCase
{
    public function test_swagger_ui_and_openapi_spec_are_available(): void
    {
        Artisan::call('l5-swagger:generate');

        $this->get('/api/documentation')
            ->assertOk()
            ->assertSee('Courier API Documentation');

        $this->getJson('/docs')
            ->assertOk()
            ->assertJsonPath('info.title', 'Courier API')
            ->assertJsonStructure([
                'paths' => [
                    '/api/couriers',
                    '/api/couriers/{courier}',
                ],
            ]);
    }
}
