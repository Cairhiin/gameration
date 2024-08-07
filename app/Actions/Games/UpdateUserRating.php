<?php

namespace App\Actions\Games;

use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserRating
{
    use AsAction;

    public function handle(Request $request, Game $game): ?float
    {
        try {
            DB::beginTransaction();

            $gameUser = $game->users()->find(Auth::id());

            if ($gameUser) {
                /* If the user already previously rated, update the average rating with their new rating */
                $game->avg_rating = (($game->avg_rating * $game->rating_count) + $request->rating - $gameUser->pivot->rating) /  $game->rating_count;

                $game->users()->sync([Auth::id() => ['rating' => $request->rating, 'updated_at' => now()]]);
            } else {
                $game->users()->attach(Auth::id(), ['rating' => $request->rating, 'id' => (string) Str::uuid(), 'created_at' => now(), 'updated_at' => now()]);
                $game->avg_rating = ($game->avg_rating * $game->rating_count + $request->rating) / ($game->rating_count + 1);
                $game->rating_count += 1;
            }

            $game->save();

            DB::commit();
        } catch (\Exception $e) {
            Log::debug($e);
            DB::rollBack();

            return null;
        }

        return $request->rating;
    }

    public function asController(Request $request, Game $game): RedirectResponse
    {
        $success = $this->handle($request, $game);

        if ($game) {
            return Redirect::route("games.show", $game->id)->with("message", "Your rating has been updated!");
        } else {
            return Redirect::route("games.show", $game->id)->with("message", "Could not update the rating!");
        }
    }
}
