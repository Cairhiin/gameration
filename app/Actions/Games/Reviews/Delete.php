<?php

namespace App\Actions\Games\Reviews;

use App\Models\Game;
use App\Enums\SystemMessage;
use Illuminate\Http\Request;
use App\Models\GameUser as Review;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(Review $review)
    {
        try {
            if ($review->rating == 0) {
                $review->delete();
            } else {
                $review->update([
                    'content' => null,
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e);

            return false;
        }

        return true;
    }

    public function asController(Game $game, Review $review)
    {
        $success = $this->handle($review);

        return Redirect::route("games.show", $game->id)->with("message", $success ? "Review" . SystemMessage::DELETE_SUCCESS : "Review" . SystemMessage::DELETE_FAILURE);
    }

    public function authorize(Request $request, Game $game, Review $review): bool
    {
        $review = $request->route()->parameter('review');

        return $review->user_id === (string) auth()->id() || Gate::allows('review:delete');
    }
}
