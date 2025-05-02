<?php

namespace App\Actions\Admin;

use App\Models\BookUser;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUnapprovedReviews
{
    use AsAction;

    public function handle(): \Illuminate\Database\Eloquent\Collection
    {
        return BookUser::where('approved', false)->where('content', '!=', null)->get();
    }
}
