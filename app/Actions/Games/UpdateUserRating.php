<?php

namespace App\Actions\Games;

use App\Models\Game;
use App\Models\User;
use App\Enums\SystemMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserRating
{
    use AsAction;

    /**
     * Updates the user's rating for a game. If the user has already rated the game,
     * the rating is updated with their new rating. If the user has not rated
     * the game before, a new rating is added to the game with a unique ID.
     *
     * @param Request $request The HTTP request containing the rating for the game.
     * @param Game $game The game to update the rating for.
     * @return float|null The updated rating for the game, or null if an error occurred.
     */
    public function handle(Request $request, Game $game): ?float
    {
        try {
            DB::beginTransaction();

            $gameUser = $game->users()->find(Auth::id());


            if ($gameUser) {
                $game->users()->updateExistingPivot(User::find(Auth::id())->id, ['rating' => $request->rating, 'updated_at' => now()]);
            } else {
                $game->users()->attach(User::find(Auth::id())->id, ['user_id' => Auth::id(), 'rating' => $request->rating, 'created_at' => now(), 'updated_at' => now()]);
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

        if ($success) {
            return Redirect::route("games.show", $game->id)->with("message", "Rating" . SystemMessage::UPDATE_SUCCESS);
        } else {
            return Redirect::route("games.show", $game->id)->with("message", "Rating" . SystemMessage::UPDATE_FAILURE);
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('game:view');
    }
}
