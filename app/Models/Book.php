<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'ISBN',
        'pages',
        'time',
        'series_book_number',
        'series_id',
        'type',
        'publisher_id',
        'published_at',
        'series_id',
        'user_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['avg_rating', 'rating_count', 'median_rating'];

    public function publisher(): HasOne
    {
        return $this->hasOne(Publisher::class, 'id', 'publisher_id');
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function creator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'book_author', 'book_id', 'author_id');
    }

    public function narrators(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'book_narrator', 'book_id', 'narrator_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('rating');
    }

    public function reviews(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot(['content', 'approved']);
    }

    public function calculateBookRating(): ?float
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
        return $this->calculateBookRating();
    }

    public function getMedianRatingAttribute(): ?float
    {
        return $this->calculateMedianRating();
    }

    public function getRatingCountAttribute(): ?int
    {
        return $this->calculateNumberOfRatings();
    }
}
