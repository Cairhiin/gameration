<?php

namespace App\Actions\Games\Image;

use App\Models\Game;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(Game $game, ActionRequest $request): bool
    {
        try {
            DB::beginTransaction();

            if ($game->image && $request->file('image')) {
                if (File::exists(storage_path('app/public/' . $game->image))) {
                    File::delete(storage_path('app/public/' . $game->image));
                }
            }

            $path = $request->file('image') ? $request->file('image')->store('images', 'public') : null;

            if ($path) {
                $game->image = $path;
                $game->save();
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return false;
        }
    }

    public function asController(ActionRequest $request, Game $game): RedirectResponse
    {
        $message = $this->handle($game, $request) ? "Image updated successfully!" : "There was a problem updating the image!";

        return Redirect::route("games.show", $game->id)->with("message", $message);
    }

    public function authorize(): bool
    {
        return Gate::allows('game:update');
    }
}
