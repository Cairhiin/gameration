<?php

namespace Tests\Feature\Actions\Developers;

use Tests\TestCase;
use App\Traits\HasTestFunctions;
use App\Traits\HasSeededDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class IndexTest extends TestCase
{
    use HasSeededDatabase;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_developers_index_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/developers');

        $response->assertStatus(200);
    }

    public function test_developers_index_page_returns_an_inertia_view(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/developers');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Developers/Index')
                ->has('developers')
                ->has(
                    'developers.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('city')
                        ->has('country')
                        ->has('year')
                        ->has('user_id')
                        ->has('games_count')
                        ->has('avg_rating')
                        ->has('created_at')
                        ->has('updated_at')
                )
        );
    }

    public function test_developers_index_page_contains_the_developers(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/developers');

        $response->assertViewMissing('No developers found');
    }
}
