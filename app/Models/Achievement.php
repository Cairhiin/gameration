<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     *
     */
    protected $fillable = [
        'title',
        'description',
        'points',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     *
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
     *
     */
    protected $appends = [];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
