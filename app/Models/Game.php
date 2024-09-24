<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Game extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;


    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = Str::uuid();
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
    protected $appends = ['avg_rating', 'rating_count'];


    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
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
        return $this->users()->avg('rating');
    }

    public function calculateNumberOfRatings(): ?int
    {
        return $this->users()->count();
    }

    public function getAvgRatingAttribute()
    {
        return $this->calculateGameRating();
    }

    public function getRatingCountAttribute()
    {
        return $this->calculateNumberOfRatings();
    }
}
