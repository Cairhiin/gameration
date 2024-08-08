<?php

namespace App\Models;

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
    protected $appends = [];

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class);
    }

    public function gamesByRating(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)->orderBy('avg_rating', 'desc');
    }

    public function gamesByDate(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)->orderBy('released_at', 'desc');
    }
}
