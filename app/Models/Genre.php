<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['games_count', 'avg_rating', 'books_count', 'books_avg_rating'];

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class);
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    public function gamesByRating(): Collection
    {
        return $this->games()->get()->sortByDesc('avg_rating');
    }

    public function gamesByDate(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)->orderBy('released_at', 'desc');
    }

    public function getGamesCountAttribute(): ?int
    {
        return $this->belongsToMany(Game::class)->count();
    }

    public function getBooksCountAttribute(): ?int
    {
        return $this->belongsToMany(Book::class)->count();
    }

    public function getAvgRatingAttribute(): ?float
    {
        /* $games = $this->games()->get();
        $avgRating = 0;
        $ratedGames = 0;

        foreach ($games as $game) {
            if ($game->getAvgRatingAttribute() != null) {
                $avgRating += $game->getAvgRatingAttribute();
                $ratedGames++;
            }
        }

        return $ratedGames ? $avgRating / $ratedGames : 0; */

        return $this->calculateAvgRating($this->games()->get());
    }

    public function getBooksAvgRatingAttribute(): ?float
    {
        return $this->calculateAvgRating($this->books()->get());
    }

    private function calculateAvgRating(Collection $collection): ?float
    {
        $sum = $collection->sum('avg_rating');
        $count = $collection->count(fn($item) => $item->avg_rating !== null);

        return $count ? $sum / $count : 0;
    }
}
