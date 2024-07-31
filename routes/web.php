<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('games')->group(function () {
    Route::get("/", \App\Actions\Games\Index::class)->name("games.index");
    Route::get("/create", \App\Actions\Games\Create::class)->name("games.create")->can('create', \App\Models\Game::class);
    Route::get("/{game}", \App\Actions\Games\Show::class)->name("games.show");
    Route::post("/", \App\Actions\Games\Store::class)->name("games.store")->can('create', \App\Models\Game::class);
    Route::post("/search", \App\Actions\Games\Search::class)->name("games.search");
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('genres')->group(function () {
    Route::get("/", \App\Actions\Genres\Index::class)->name("genres.index");
    Route::get("/create", \App\Actions\Genres\Create::class)->name("genres.create")->can('create', \App\Models\Genre::class);
    Route::post("/", \App\Actions\Genres\Store::class)->name("genres.store")->can('create', \App\Models\Genre::class);
    Route::post("/search", \App\Actions\Genres\Search::class)->name("genres.search");
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('developers')->group(function () {
    Route::get("/", \App\Actions\Developers\Index::class)->name("developers.index");
    Route::get("/create", \App\Actions\Developers\Create::class)->name("developers.create")->can('create', \App\Models\Developer::class);
    Route::post("/", \App\Actions\Developers\Store::class)->name("developers.store")->can('create', \App\Models\Developer::class);
    Route::get("/{developer}/edit", \App\Actions\Developers\Edit::class)->name("developers.edit")->can('update', 'developer');
    Route::put("/{developer}", \App\Actions\Developers\Update::class)->name("developers.update")->can('update', 'developer');
    Route::get("/{developer}", \App\Actions\Developers\Show::class)->name("developers.show");
    Route::post("/search", \App\Actions\Developers\Search::class)->name("developers.search");
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('publishers')->group(function () {
    Route::get("/", \App\Actions\Publishers\Index::class)->name("publishers.index");
    Route::post("/", \App\Actions\Publishers\Store::class)->name("publishers.store")->can('create', \App\Models\Publisher::class);
    Route::get("/create", \App\Actions\Publishers\Create::class)->name("publishers.create")->can('create', \App\Models\Publisher::class);
    Route::get("/{publisher}/edit", \App\Actions\Publishers\Edit::class)->name("publishers.edit")->can('update', 'publisher');
    Route::put("/{publisher}", \App\Actions\Publishers\Update::class)->name("publishers.update")->can('update', 'publisher');
    Route::get("/{publisher}", \App\Actions\Publishers\Show::class)->name("publishers.show");
    Route::post("/search", \App\Actions\Publishers\Search::class)->name("publishers.search");
});
