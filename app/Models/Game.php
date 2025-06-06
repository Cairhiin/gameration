<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;


    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'released_at',
        'user_id',
        'avg_rating',
        'rating_count',
        'developer_id',
        'publisher_id',
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
    protected $appends = ['avg_rating', 'rating_count', 'median_rating'];


    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class)->select(['genre_id', 'name']);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class)->select(['id', 'name']);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class)->select(['id', 'name']);
    }

    public function creator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('rating');
    }

    public function calculateGameRating(): ?float
    {
        return $this->users()->withPivot('rating')->where('rating', '>', 0)->get()->average('pivot.rating');
    }

    public function calculateMedianRating(): ?float
    {
        return $this->users()->withPivot('rating')->where('rating', '>', 0)->get()->median('pivot.rating');
    }

    public function calculateNumberOfRatings(): ?int
    {
        return $this->users()->withPivot('rating')->where('rating', '>', 0)->count();
    }

    public function getAvgRatingAttribute(): ?float
    {
        return $this->calculateGameRating() ?? 0.0;
    }

    public function getMedianRatingAttribute(): ?float
    {
        return $this->calculateMedianRating() ?? 0.0;
    }

    public function getRatingCountAttribute(): ?int
    {
        return $this->calculateNumberOfRatings();
    }
}
