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

Route::group(['prefix' => 'games'], function () {
    Route::middleware(['auth:sanctum', 'verified'])->get("/", \App\Actions\Games\Index::class)->name("games.index");
    Route::middleware(['auth:sanctum', 'verified'])->get("/create", \App\Actions\Games\Create::class)->name("games.create");
    Route::middleware(['auth:sanctum', 'verified'])->get("/{game}", \App\Actions\Games\Show::class)->name("games.show");
    Route::middleware(['auth:sanctum', 'verified'])->post("/", \App\Actions\Games\Store::class)->name("games.store");
    Route::middleware(['auth:sanctum', 'verified'])->post("/search", \App\Actions\Games\Search::class)->name("games.search");
});

Route::group(['prefix' => 'genres'], function () {
    Route::middleware(['auth:sanctum', 'verified'])->get("/", \App\Actions\Genres\Index::class)->name("genres.index");
    Route::middleware(['auth:sanctum', 'verified'])->get("/create", \App\Actions\Genres\Create::class)->name("genres.create");
    Route::middleware(['auth:sanctum', 'verified'])->post("/", \App\Actions\Genres\Store::class)->name("genres.store");
    Route::middleware(['auth:sanctum', 'verified'])->post("/search", \App\Actions\Genres\Search::class)->name("genres.search");
});

Route::group(['prefix' => 'developers'], function () {
    Route::middleware(['auth:sanctum', 'verified'])->get("/", \App\Actions\Developers\Index::class)->name("developers.index");
    Route::middleware(['auth:sanctum', 'verified'])->get("/create", \App\Actions\Developers\Create::class)->name("developers.create");
    Route::middleware(['auth:sanctum', 'verified'])->post("/", \App\Actions\Developers\Store::class)->name("developers.store");
    Route::middleware(['auth:sanctum', 'verified'])->post("/search", \App\Actions\Developers\Search::class)->name("developers.search");
});

Route::group(['prefix' => 'publishers'], function () {
    Route::middleware(['auth:sanctum', 'verified'])->get("/", \App\Actions\Publishers\Index::class)->name("publishers.index");
    Route::middleware(['auth:sanctum', 'verified'])->get("/create", \App\Actions\Publishers\Create::class)->name("publishers.create");
    Route::middleware(['auth:sanctum', 'verified'])->post("/", \App\Actions\Publishers\Store::class)->name("publishers.store");
    Route::middleware(['auth:sanctum', 'verified'])->post("/search", \App\Actions\Publishers\Search::class)->name("publishers.search");
});
