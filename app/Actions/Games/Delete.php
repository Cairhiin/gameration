<?php

namespace App\Actions\Games;

use App\Models\Game;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\File;
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
            if (File::exists(storage_path('app/public/' . $game->image))) {
                File::delete(storage_path('app/public/' . $game->image));
            }

            return $game->delete();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function asController(Game $game): RedirectResponse
    {
        $success = $this->handle($game);

        if (!$success) {
            return Redirect::route("games.show", $game->id)->with("message", "Game" . SystemMessage::DELETE_FAILURE);
        } else {
            return Redirect::route("games.index")->with("message", "Game" . SystemMessage::DELETE_SUCCESS);
        }
    }

    public function authorize(Game $game): bool
    {
        return Gate::allows('game:delete', $game);
    }
}
