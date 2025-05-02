<?php

namespace Tests\Feature\Actions\Admin;

use App\Actions\Admin\GetNewUsers;
use Tests\TestCase;
use App\Models\User;
use App\Enums\RoleName;
use App\Traits\HasTestFunctions;
use App\Traits\HasRolesAndPermissions;
use Inertia\Testing\AssertableInertia as Assert;

class IndexTest extends TestCase
{
    use HasRolesAndPermissions;
    use HasTestFunctions;

    public function setUp(): void
    {
        parent::setUp();

        $this->createUserWithRoleAndPermissions();
    }

    public function test_index_page_is_accessible_by_admin()
    {
        $this->addRoleToUser($this->user, RoleName::ADMIN);

        $response = $this->actingAs($this->user)
            ->json('GET', 'admin');

        $response->assertStatus(200);
    }

    public function test_index_page_is_accessible_by_moderator()
    {
        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user)
            ->json('GET', 'admin');

        $response->assertStatus(200);
    }

    public function test_index_page_is_not_accessible_by_regular_user()
    {
        $response = $this->actingAs($this->user)
            ->json('GET', 'admin');

        $response->assertStatus(403);
    }

    public function test_index_page_returns_all_unapproved_reviews()
    {
        $books = $this->createBooks(2);
        $users = $this->createUsers(5);

        $books->each(function ($book) use ($users) {
            $users->each(function ($user) use ($book) {
                $user->books()->attach($book->id, [
                    'content' => 'This is a review',
                    'rating' => rand(3, 5),
                    'approved' => false,
                ]);
            });
        });

        $this->addRoleToUser($this->user, RoleName::MODERATOR);

        $response = $this->actingAs($this->user)
            ->json('GET', 'admin');

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Admin/Index')
                ->has('unapprovedReviews', 10)
                ->has(
                    'unapprovedReviews.0',
                    fn(Assert $page) => $page
                        ->where('content', 'This is a review')
                        ->etc()
                )
                ->has('newUsers')
        );
    }
}
