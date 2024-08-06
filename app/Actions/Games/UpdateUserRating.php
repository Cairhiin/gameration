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

            if ($game->users->find(Auth::id())) {
                Log::debug($request->rating);
                $game->users()->sync([Auth::id() => ['rating' => $request->rating]]);
            } else {
                $game->users()->attach(Auth::id(), ['rating' => $request->rating, 'id' => (string) Str::uuid()]);
            }

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
