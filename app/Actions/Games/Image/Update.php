<?php

namespace App\Actions\Games\Image;

use App\Models\Game;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $message = $this->handle($game, $request) ? "Image" . SystemMessage::UPDATE_SUCCESS : "Image" . SystemMessage::UPDATE_FAILURE;

        return Redirect::route("games.show", $game->id)->with("message", $message);
    }

    public function authorize(): bool
    {
        return Gate::allows('game:update');
    }

    /**
     * Returns an array of validation rules for the `image` field.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'image' => ['nullable', 'mimes:jpg,bmp,png', 'max:2048']
        ];
    }

    /**
     * Returns the validation error bag for the 'createGame' validation.
     *
     * @return string The validation error bag name.
     */
    public function getValidationErrorBag(): string
    {
        return 'updateImage';
    }

    /**
     * Returns an array of validation messages for 'image' field.
     *
     * @return array An associative array where the keys are the validation rules and the values are the corresponding error messages.
     */
    public function getValidationMessages(): array
    {
        return [
            'image.max' => 'The image size must be less than 2MB.',
            'image.mimes' => 'Unsupported image format. Supported formats are: jpg, bmp, png.',
        ];
    }
}
