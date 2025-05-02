<?php

namespace App\Actions\Admin;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetNewUsers
{
    use AsAction;

    public function handle(): \Illuminate\Database\Eloquent\Collection
    {
        $users = User::where('created_at', '>=', now()->subDays(1))
            ->orderBy('created_at', 'desc')
            ->get();

        return $users;
    }
}
