<?php

namespace Tests\Feature\Actions\Games;

use Closure;
use Mockery;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Game;
use App\Traits\HasTestFunctions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Traits\HasRolesAndPermissions;

class CacheTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_it_should_cache_a_paginated_list_of_games_successfully_on_the_main_page(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with("gamesByDatePaginated", Mockery::any(), Mockery::on(function (Closure $closure) {
                return $closure() instanceof \Illuminate\Pagination\LengthAwarePaginator;
            }))
            ->andReturn(Game::paginate(15));

        $this->actingAs($this->user, 'web')->get('/games');
    }

    public function test_it_should_cache_a_paginated_list_of_games_successfully_on_sorted_by_date(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with("gamesByDatePaginated", Mockery::any(), Mockery::on(function (Closure $closure) {
                return $closure() instanceof \Illuminate\Pagination\LengthAwarePaginator;
            }))
            ->andReturn(Game::paginate(15));

        $this->actingAs($this->user, 'web')->get('/games/?sortBy=released_at&sortOrder=desc');
    }

    public function test_it_should_cache_a_collection_of_all_games_successfully_on_sorted_by_rating(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with("games", Mockery::any(), Mockery::on(function (Closure $closure) {
                return $closure() instanceof Collection;
            }))
            ->andReturn(Game::all());

        $this->actingAs($this->user, 'web')->get('/games/?sortBy=avg_rating&sortOrder=desc');
    }

    public function test_it_should_cache_a_paginated_list_of_games_successfully_on_sorted_by_name(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with("gamesByNamePaginated", Mockery::any(), Mockery::on(function (Closure $closure) {
                return $closure() instanceof \Illuminate\Pagination\LengthAwarePaginator;
            }))
            ->andReturn(Game::paginate(15));

        $this->actingAs($this->user, 'web')->get('/games/?sortBy=name&sortOrder=desc');
    }


    public function test_it_should_cache_a_collection_of_all_games_successfully_on_sorted_by_popularity(): void
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with("gamesByPopularity", Mockery::any(), Mockery::on(function (Closure $closure) {
                return $closure() instanceof Collection;
            }))
            ->andReturn(Game::whereYear('released_at', Carbon::now()->year)->get());

        $this->actingAs($this->user, 'web')->get('/games/?sortBy=popular&sortOrder=desc');
    }
}
