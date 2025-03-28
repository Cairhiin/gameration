<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Role;
use App\Models\User;
use App\Enums\RoleName;
use App\Models\Message;
use App\Models\GameUser;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Permission;
use App\Models\Achievement;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_model_can_create_a_user(): void
    {
        $user = User::factory()->create([
            'username' => 'John Doe',
        ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'John Doe']);
    }

    public function test_user_model_can_retrieve_a_user(): void
    {
        User::factory()->create([
            'username' => 'John Doe',
            'email' => 'test@example.com',
            'name' => 'John Doe'
        ]);

        $user = User::first();

        $this->assertEquals($user->name, 'John Doe');
        $this->assertEquals($user->email, 'test@example.com');
        $this->assertEquals($user->username, 'John Doe');
    }

    public function test_user_model_appends_a_profile_photo(): void
    {
        $user = User::factory()->create();
        $this->assertStringContainsString('https://ui-avatars.com/api/?name', $user->profile_photo_url);
    }

    public function test_user_model_appends_a_ratings_count(): void
    {
        $user = User::factory()->create();
        $this->assertEquals(0, $user->ratings_count);
    }

    public function test_user_model_appends_a_games_count(): void
    {
        $user = User::factory()->create();
        $this->assertEquals(0, $user->games_count);
    }

    public function test_user_model_hides_sensitive_information(): void
    {
        $user = User::factory()->create();
        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->assertArrayNotHasKey('remember_token', $user->toArray());
        $this->assertArrayNotHasKey('two_factor_recovery_codes', $user->toArray());
        $this->assertArrayNotHasKey('two_factor_secret', $user->toArray());
    }

    public function test_user_model_has_user_games_added_relationship(): void
    {
        $user = User::factory()->create();
        Game::factory(['user_id' => $user->id])->create();

        $games = $user->gamesAdded;

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $games);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $user->gamesAdded());
        $this->assertInstanceOf(Game::class, $games->first());
    }

    public function test_user_model_has_game_user_relationship(): void
    {
        $user = User::factory()->create();
        $game = Game::factory()->create();

        // Create a game user relationship: review and rating
        GameUser::factory(['user_id' => $user->id, 'game_id' => $game->id, 'rating' => 1])->create();

        $games = $user->games;

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $games);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->games());
        $this->assertEquals(1, $games->first()->pivot->rating);
    }

    public function test_user_model_casts_email_verified_at_to_datetime(): void
    {
        $user = User::factory()->create();
        $this->assertInstanceOf('Illuminate\Support\Carbon', $user->email_verified_at);
    }

    public function test_user_model_casts_name_to_encrypted(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);

        $this->assertEquals('John Doe', Crypt::decryptString($user->getRawOriginal('name')));
    }

    public function test_user_model_casts_email_to_encrypted(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $this->assertEquals('test@example.com', Crypt::decryptString($user->getRawOriginal('email')));
    }

    public function test_user_model_addRole_method_adds_a_role(): void
    {
        $user = User::factory()->create();
        Role::factory(['name' => RoleName::USER->value])->create();

        $user->assignRole(RoleName::USER);

        $this->assertTrue($user->hasRole(RoleName::USER));
    }

    public function test_user_model_has_roles_relationship(): void
    {
        $user = User::factory()->create();
        Role::factory(['name' => RoleName::USER->value])->create();

        $user->assignRole(RoleName::USER);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->roles());
        $this->assertInstanceOf(Role::class, $user->roles->first());
    }

    public function test_user_model_has_a_specific_role(): void
    {
        $user = User::factory()->create();
        Role::factory(['name' => RoleName::USER->value])->create();

        $user->assignRole(RoleName::USER);

        $this->assertTrue($user->hasRole(RoleName::USER));
    }

    public function test_user_model_does_not_have_a_specific_role(): void
    {
        $user = User::factory()->create();
        Role::factory(['name' => RoleName::USER->value])->create();

        $this->assertFalse($user->hasRole(RoleName::USER));
    }

    public function test_user_model_has_permissions(): void
    {
        $user = User::factory()->create();

        $role = Role::factory(['name' => RoleName::USER->value])->create();
        $permissions[] = Permission::create(['name' => 'create:game'])->id;
        $role->permissions()->sync($permissions);

        $user->assignRole(RoleName::USER);

        $this->assertEquals('create:game', $user->permissions()[0]);
    }

    public function test_user_model_has_a_specific_permission(): void
    {
        $user = User::factory()->create();

        $role = Role::factory(['name' => RoleName::USER->value])->create();
        $permissions[] = Permission::create(['name' => 'create:game'])->id;
        $role->permissions()->sync($permissions);

        $user->assignRole(RoleName::USER);

        $this->assertTrue($user->hasPermission('create:game'));
    }

    public function test_user_model_isUser_method_returns_true_if_user_is_a_normal_user(): void
    {
        $user = User::factory()->create();
        Role::factory(['name' => RoleName::USER->value])->create();

        $user->assignRole(RoleName::USER);

        $this->assertTrue($user->isUser());
    }

    public function test_user_model_isModerator_method_returns_true_if_user_is_a_moderator(): void
    {
        $user = User::factory()->create();
        Role::factory(['name' => RoleName::MODERATOR->value])->create();

        $user->assignRole(RoleName::MODERATOR);

        $this->assertTrue($user->isModerator());
    }

    public function test_user_model_isAdmin_method_returns_true_if_user_is_an_admin(): void
    {
        $user = User::factory()->create();
        Role::factory(['name' => RoleName::ADMIN->value])->create();

        $user->assignRole(RoleName::ADMIN);

        $this->assertTrue($user->isAdmin());
    }

    public function test_user_model_has_developer_relationship(): void
    {
        $user = User::factory()->create();
        $developer = Developer::factory(['user_id' => $user->id])->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $user->developers());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->developers);
        $this->assertInstanceOf(Developer::class, $user->developers->first());
    }

    public function test_user_model_has_publisher_relationship(): void
    {
        $user = User::factory()->create();
        $publisher = Publisher::factory(['user_id' => $user->id])->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $user->publishers());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->publishers);
        $this->assertInstanceOf(Publisher::class, $user->publishers->first());
    }

    public function test_user_model_has_sent_messages_relationship(): void
    {
        $user = User::factory()->create();
        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => User::factory()->create()->id,
            'subject' => 'Hello',
            'read' => false,
            'archived' => false,
            'body' => 'Hello, World!',
        ]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $user->sentMessages());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->sentMessages);
        $this->assertInstanceOf(Message::class, $user->sentMessages->first());
    }

    public function test_user_model_has_received_messages_relationship(): void
    {
        $user = User::factory()->create();
        $message = Message::create([
            'sender_id' => User::factory()->create()->id,
            'receiver_id' => $user->id,
            'subject' => 'Hello',
            'read' => false,
            'archived' => false,
            'body' => 'Hello, World!',
        ]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $user->receivedMessages());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->receivedMessages);
        $this->assertInstanceOf(Message::class, $user->receivedMessages->first());
    }

    public function test_user_model_has_friends_relationship(): void
    {
        $user = User::factory()->create();
        $friend1 = User::factory()->create();
        $friend2 = User::factory()->create();

        $user->friendsOfMine()->attach($friend1, ['accepted' => 1]);
        $user->friendOf()->attach($friend2, ['accepted' => 1]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->friendsOfMine());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->friendOf());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->friends);
        $this->assertInstanceOf(User::class, $user->friends->first());
    }

    public function test_user_model_has_achievements_relationship(): void
    {
        $user = User::factory()->create();
        Achievement::create([
            'name' => 'test',
            'description' => 'lorem ipsum',
            'points' => 10,
            'image' => 'image.jpg',
        ]);

        $user->achievements()->attach(Achievement::first()->id);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->achievements());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->achievements);
        $this->assertInstanceOf(Achievement::class, $user->achievements->first());
    }
}
