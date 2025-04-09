<?php

namespace App\Actions\Books;

use App\Models\Genre;
use App\Enums\GenreType;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class GetAllGenres
{
    use AsAction;

    public function handle(): Collection
    {
        return Genre::where('type', GenreType::BOOK)->orWhere('type', GenreType::OTHER)->orderBy('name', 'asc')->get();
    }
}
