<?php

namespace Tests\Feature\Actions\Genres;

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

    public function test_genres_index_page_returns_a_successful_response(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres');

        $response->assertStatus(200);
    }

    public function test_genres_index_page_returns_an_inertia_view(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Genres/Index')
                ->has('genres', 21)
                ->has(
                    'genres.0',
                    fn(Assert $page) => $page
                        ->has('id')
                        ->has('name')
                        ->has('games_count')
                        ->has('avg_rating')
                        ->has('created_at')
                        ->has('updated_at')
                )
        );
    }

    public function test_genres_index_page_contains_the_genres(): void
    {
        $response = $this->actingAs($this->user, 'web')->get('/genres');

        $response->assertViewMissing('No genres found');
    }
}
