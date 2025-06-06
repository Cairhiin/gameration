<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

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

Route::get('/', \App\Actions\Home\Welcome::class)->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', \App\Actions\Profile\Dashboard::class)->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('games')->group(function () {
    Route::get("/", \App\Actions\Games\Index::class)->name("games.index");
    Route::post("/", \App\Actions\Games\Store::class)->name("games.store");
    Route::get("/create", \App\Actions\Games\Create::class)->name("games.create");
    Route::get("/{game}/edit", \App\Actions\Games\Edit::class)->name("games.edit");
    Route::get("/{game}/image/edit", \App\Actions\Games\Image\Edit::class)->name("games.image.edit");
    Route::delete("/{game}", \App\Actions\Games\Delete::class)->name("games.destroy");
    Route::get("/{game}", \App\Actions\Games\Show::class)->name("games.show");
    Route::put("/{game}", \App\Actions\Games\Update::class)->name("games.update");
    Route::put("/{game}/image", \App\Actions\Games\Image\Update::class)->name("games.image.update");
    Route::post("/search", \App\Actions\Games\Search::class)->name("games.search");
    Route::post("/{game}/rate", \App\Actions\Games\UpdateUserRating::class)->name("games.rate");
    Route::get("/{user}/ratings", \App\Actions\Games\ShowUserRatings::class)->name("games.ratings");
    Route::get("/{game}/reviews", \App\Actions\Games\Reviews\Index::class)->name("games.reviews.index");
    //Route::get("/{game}/reviews/create", \App\Actions\Games\Reviews\Create::class)->name("games.reviews.create");
    Route::post("/{game}/reviews", \App\Actions\Games\Reviews\Store::class)->name("games.reviews.store");
    //Route::get("/{game}/reviews/{review}/edit", [\App\Actions\Games\Reviews\Edit::class, 'asController'])->name("games.reviews.edit");
    Route::put("/{game}/reviews/{review}", [\App\Actions\Games\Reviews\Update::class, 'asController'])->name("games.reviews.update");
    Route::delete("/{game}/reviews/{review}", \App\Actions\Games\Reviews\Delete::class)->name("games.reviews.destroy");
    Route::get("/{game}/reviews/{review}", \App\Actions\Games\Reviews\Show::class)->name("games.reviews.show");
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('genres')->group(function () {
    Route::get("/", \App\Actions\Genres\Index::class)->name("genres.index");
    Route::get("/create", \App\Actions\Genres\Create::class)->name("genres.create");
    Route::get("/{genre}/edit", \App\Actions\Genres\Edit::class)->name("genres.edit");
    Route::delete("/{genre}", \App\Actions\Genres\Delete::class)->name("genres.destroy");
    Route::post("/", \App\Actions\Genres\Store::class)->name("genres.store");
    Route::put("/{genre}", \App\Actions\Genres\Update::class)->name("genres.update");
    Route::get("/{genre}", \App\Actions\Genres\Show::class)->name("genres.show");
    Route::post("/search", \App\Actions\Genres\Search::class)->name("genres.search");
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('developers')->group(function () {
    Route::get("/", \App\Actions\Developers\Index::class)->name("developers.index");
    Route::get("/create", \App\Actions\Developers\Create::class)->name("developers.create");
    Route::post("/", \App\Actions\Developers\Store::class)->name("developers.store");
    Route::get("/{developer}/edit", \App\Actions\Developers\Edit::class)->name("developers.edit");
    Route::delete("/{developer}", \App\Actions\Developers\Delete::class)->name("developers.destroy");
    Route::put("/{developer}", \App\Actions\Developers\Update::class)->name("developers.update");
    Route::get("/{developer}", \App\Actions\Developers\Show::class)->name("developers.show");
    Route::post("/search", \App\Actions\Developers\Search::class)->name("developers.search");
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('publishers')->group(function () {
    Route::get("/", \App\Actions\Publishers\Index::class)->name("publishers.index");
    Route::post("/", \App\Actions\Publishers\Store::class)->name("publishers.store");
    Route::get("/create", \App\Actions\Publishers\Create::class)->name("publishers.create");
    Route::get("/{publisher}/edit", \App\Actions\Publishers\Edit::class)->name("publishers.edit");
    Route::delete("/{publisher}", \App\Actions\Publishers\Delete::class)->name("publishers.destroy");
    Route::put("/{publisher}", \App\Actions\Publishers\Update::class)->name("publishers.update");
    Route::get("/{publisher}", \App\Actions\Publishers\Show::class)->name("publishers.show");
    Route::post("/search", \App\Actions\Publishers\Search::class)->name("publishers.search");
});

Route::middleware(['auth:sanctum', 'verified', 'web'])->prefix('user/profile')->group(function () {
    Route::get("/friends", \App\Actions\Profile\Friends\Index::class)->name("profile.friends.index");
    Route::post("/friends", \App\Actions\Profile\Friends\Store::class)->name("profile.friends.store");
    Route::delete("/friends/{user}", \App\Actions\Profile\Friends\Delete::class)->name("profile.friends.destroy");
    Route::put("/friends/{user}/update", \App\Actions\Profile\Friends\Update::class)->name("profile.friends.update");
    Route::get("/friends/{user}/messages", \App\Actions\Profile\Friends\Messages\GetMessages::class)->name("profile.friends.messages");
    Route::post("/friends/{user}/messages", \App\Actions\Profile\Friends\Messages\Store::class)->name("profile.friends.messages.store");
    Route::delete("/friends/{user}/messages/{message}/delete", \App\Actions\Profile\Friends\Messages\Delete::class)->name("profile.friends.messages.destroy");
    Route::put("/friends/{user}/messages/{message}/update", \App\Actions\Profile\Friends\Messages\Update::class)->name("profile.friends.messages.update");
    Route::get("/ratings", \App\Actions\Profile\Ratings\Index::class)->name("profile.ratings.index");
});

Route::middleware(['auth:sanctum', 'verified', 'web'])->prefix('users')->group(function () {
    Route::post("/search", \App\Actions\Users\Search::class)->name("users.search");
});

Route::middleware(['auth:sanctum', 'verified', 'web'])->prefix('achievements')->group(function () {
    Route::get("/", \App\Actions\Achievements\Index::class)->name("achievements.index");
    Route::post("/", \App\Actions\Achievements\Store::class)->name("achievements.store");
    Route::get("/create", \App\Actions\Achievements\Create::class)->name("achievements.create");
    Route::get("/{achievement}/edit", \App\Actions\Achievements\Edit::class)->name("achievements.edit");
    Route::delete("/{achievement}", \App\Actions\Achievements\Delete::class)->name("achievements.destroy");
    Route::put("/{achievement}", \App\Actions\Achievements\Update::class)->name("achievements.update");
    Route::get("/{achievement}", \App\Actions\Achievements\Show::class)->name("achievements.show");
});

Route::middleware(['auth:sanctum', 'verified', 'web'])->prefix('books')->group(function () {
    Route::get("/", \App\Actions\Books\Index::class)->name("books.index");
    Route::post("/", \App\Actions\Books\Store::class)->name("books.store");
    Route::get("/create", \App\Actions\Books\Create::class)->name("books.create");
    Route::get("/{book}/edit", \App\Actions\Books\Edit::class)->name("books.edit");
    Route::delete("/{book}", \App\Actions\Books\Destroy::class)->name("books.destroy");
    Route::put("/{book}", \App\Actions\Books\Update::class)->name("books.update");
    Route::get("/{book}", \App\Actions\Books\Show::class)->name("books.show");
    Route::get("/{book}/image/edit", \App\Actions\Books\Image\Edit::class)->name("books.image.edit");
    Route::put("/{book}/image", \App\Actions\Books\Image\Update::class)->name("books.image.update");
    Route::delete("/{book}/image", \App\Actions\Books\Image\Destroy::class)->name("books.image.destroy");
    Route::post("/search", App\Actions\Books\Index::class)->name("books.search");
    Route::post("/{book}/rate", App\Actions\Books\UpdateUserRating::class)->name("books.rate");
    Route::post("/authors/search", App\Actions\Books\Authors\Search::class)->name("books.authors.search");
    Route::post("/narrators/search", App\Actions\Books\Narrators\Search::class)->name("books.narrators.search");
    Route::post("/series/search", App\Actions\Books\Series\Search::class)->name("books.series.search");
    Route::get("/series/create", App\Actions\Books\Series\Create::class)->name("books.series.create");
    Route::post("/series", App\Actions\Books\Series\Store::class)->name("books.series.store");
    Route::get("/series/{series}", App\Actions\Books\Series\Show::class)->name("books.series.show");
});

Route::middleware(['auth:sanctum', 'verified', 'web'])->prefix('persons')->group(function () {
    Route::get("/", \App\Actions\Persons\Index::class)->name("persons.index");
    Route::post("/", \App\Actions\Persons\Store::class)->name("persons.store");
    Route::get("/create", \App\Actions\Persons\Create::class)->name("persons.create");
    Route::get("/{person}/edit", \App\Actions\Persons\Edit::class)->name("persons.edit");
    Route::delete("/{person}", \App\Actions\Persons\Destroy::class)->name("persons.destroy");
    Route::put("/{person}", \App\Actions\Persons\Update::class)->name("persons.update");
    Route::get("/{person}", \App\Actions\Persons\Show::class)->name("persons.show");
    Route::post("/search", \App\Actions\Persons\Search::class)->name("persons.search");
});

Route::middleware(['auth:sanctum', 'verified', 'web'])->prefix('admin')->group(function () {
    Route::get("/", \App\Actions\Admin\Index::class)->name("admin.index");
    Route::post("/import/authors", \App\Actions\Admin\importAuthors::class)->name("admin.import.authors");
    Route::post("/import/books", \App\Actions\Admin\importBooks::class)->name("admin.import.books");
});
