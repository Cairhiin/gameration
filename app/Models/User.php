<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleName;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

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

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('username', $request->username)->first() ?? User::where('email', $request->username)->first();

            if (
                $user &&
                Hash::check($request->password, $user->password)
            ) {
                return $user;
            }
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email',
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
        'name' => 'encrypted',
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

    /**
     * Provide a default profile photo URL if no profile photo has been uploaded.
     *
     * This URL will use the first two letters of the user's username
     * to generate a unique identicon. The identicon will be generated by the
     * UI Avatars API. Overwrites Jetstream's defaultProfilePhotoUrl method.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        if (strlen($this->username) < 2) {
            $username = mb_substr($this->username, 0, 1);
        } else {
            $username = trim(
                mb_substr($this->username, 0, 2),
            );
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($username) . '&color=10B981&background=1D232E';
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class)->withPivot('rating');
    }

    public function gamesAdded(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(RoleName $role): bool
    {
        return $this->roles()->where('name', $role->value)->exists();
    }

    public function permissions(): array
    {
        return $this->roles()->with('permissions')->get()
            ->map(function ($role) {
                return $role->permissions->pluck('name');
            })->flatten()->values()->unique()->toArray();
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions(), true);
    }

    public function isUser(): bool
    {
        return $this->hasRole(RoleName::USER);
    }

    public function isModerator(): bool
    {
        return $this->hasRole(RoleName::MODERATOR);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleName::ADMIN);
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

    /* Ratings */
    public function getRatingsCountAttribute(): ?int
    {
        return $this->games()->count();
    }

    public function getGamesCountAttribute(): ?int
    {
        return $this->gamesAdded()->count();
    }

    /* Friend List */
    function friendsOfMine(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('accepted', '=', 1)
            ->withPivot('accepted', 'user_id', 'friend_id')
            //->select(array('friend_id', 'username'))
        ;
    }

    function friendOf(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->wherePivot('accepted', '=', 1)
            ->withPivot('accepted', 'user_id', 'friend_id')
            //->select(array('friend_id', 'username'))
        ;
    }

    public function getFriendsAttribute(): Collection
    {
        if (! array_key_exists('friends', $this->relations)) $this->loadFriends();

        return $this->getRelation('friends');
    }

    public function pendingFriends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('accepted', '=', 0)
            ->withPivot('accepted', 'user_id', 'friend_id')
            //->select(array('friend_id', 'username'))
        ;
    }

    public function pendingInvites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->wherePivot('accepted', '=', 0)
            ->withPivot('accepted', 'user_id', 'friend_id')
            //->select(array('friend_id', 'username'))
        ;
    }

    protected function loadFriends(): void
    {
        if (! array_key_exists('friends', $this->relations)) {
            $friends = $this->mergeFriends();

            $this->setRelation('friends', $friends);
        }
    }

    public function friends(): Collection
    {
        return $this->mergeFriends();
    }

    protected function mergeFriends(): Collection
    {
        return $this->friendsOfMine->mergeRecursive($this->friendOf);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
