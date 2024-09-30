<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;


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
        'username',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'ratings_count',
        'games_count'
    ];

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)->withPivot('rating');
    }

    public function gamesAdded(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isModerator(): bool
    {
        return $this->role->slug === 'admin' || $this->role->slug === 'moderator';
    }

    public function isAdmin(): bool
    {
        return $this->role->slug === 'admin';
    }

    public function developers(): HasMany
    {
        return $this->hasMany(Developer::class);
    }

    public function publishers(): HasMany
    {
        return $this->hasMany(Publisher::class);
    }

    public function game_user(): HasMany
    {
        return $this->hasMany(GameUser::class);
    }

    public function getRatingsCountAttribute(): ?int
    {
        return $this->games()->count();
    }

    public function getGamesCountAttribute(): ?int
    {
        return $this->gamesAdded()->count();
    }
}
