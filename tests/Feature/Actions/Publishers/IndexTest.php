<?php

namespace Tests\Feature\Actions\Publishers;

use App\Models\Publisher;
use Tests\TestCase;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class IndexTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_publishers_index_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/publishers');

        $response->assertStatus(200);
    }

    public function test_publishers_index_page_returns_an_inertia_view(): void
    {
        Publisher::factory()->create();

        $response = $this->actingAs($this->user, 'web')->get('/publishers');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Publishers/Index')
                ->has('publishers')
                ->has(
                    'publishers.0',
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

    public function test_publishers_index_page_contains_the_publishers(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/publishers');

        $response->assertViewMissing('No publishers found');
    }
}
