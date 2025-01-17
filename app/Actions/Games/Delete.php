<?php

namespace App\Actions\Games;

use App\Models\Game;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(Game $game)
    {
        try {
            $game->delete();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function asController(Game $game): RedirectResponse
    {
        $game = $this->handle($game);

        if (!$game) {
            return Redirect::route("games.show", $game->id)->with("message", "We could not delete the game!");
        } else {
            return Redirect::route("games.index")->with("message", "The game has been deleted!");
        }
    }

    public function authorize(Game $game): bool
    {
        return Gate::allows('game:delete', $game);
    }
}
