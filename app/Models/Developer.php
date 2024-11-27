<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'country',
        'city',
        'year'
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
    protected $appends = ['games_count', 'avg_rating'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function getGamesCountAttribute(): int
    {
        return $this->games()->count();
    }

    public function getAvgRatingAttribute(): ?float
    {
        $games = $this->games()->get();
        $avgRating = 0;
        $ratedGames = 0;

        foreach ($games as $game) {
            if ($game->getAvgRatingAttribute() != null) {
                $avgRating += $game->getAvgRatingAttribute();
                $ratedGames++;
            }
        }

        return $ratedGames ? $avgRating / $ratedGames : 0;
    }
}
