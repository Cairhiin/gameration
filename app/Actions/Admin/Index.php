<?php

namespace App\Actions\Admin;

use App\Enums\BookType;
use App\Models\Book;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Person;
use Lorisleiva\Actions\Concerns\AsAction;

class Index
{
    use AsAction;

    public function handle(): Response
    {
        $newUsers = GetNewUsers::run();
        $unapprovedReviews = GetUnapprovedReviews::run();

        $data = [
            'totalUsers' => User::count(),
            'totalBooks' => Book::where('type', BookType::PHYSICAL)->count(),
            'totalAuthors' => Person::where('type', 'author')->count()
        ];

        return Inertia::render('Admin/Index', [
            'data' => $data,
            'newUsers' => $newUsers,
            'unapprovedReviews' => $unapprovedReviews,
        ]);
    }

    public function asController(): Response
    {
        return $this->handle();
    }

    public function authorize(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isModerator();
    }
}
