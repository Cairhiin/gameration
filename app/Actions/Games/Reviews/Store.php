<?php

namespace App\Actions\Games\Reviews;

use App\Models\Game;
use App\Models\GameUser;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(ActionRequest $request, Game $game): ?GameUser
    {
        $gameUser = GameUser::where('game_id', $game->id)->where('user_id', auth()->id());

        if ($gameUser->exists() && $gameUser->first()->content) {
            return null;
        }

        return GameUser::updateOrCreate([
            'game_id' => $game->id,
            'user_id' => auth()->id(),
        ], [
            'content' => $request->content,
            'game_id' => $game->id,
            'user_id' => auth()->id(),
            'approved' => User::find(auth()->id())->isAdmin() || User::find(auth()->id())->isModerator() ? true : false,
        ]);
    }

    public function asController(ActionRequest $request, Game $game): RedirectResponse
    {
        $review = $this->handle($request, $game);

        if ($review && !$review->approved) {
            return Redirect::route("games.show", $game->id)->with("message", "Your review is awaiting approval!");
        } elseif ($review) {
            return Redirect::route("games.show", $game->id)->with("message", "Your review has been added successfully!");
        } else {
            return Redirect::route("games.show", $game->id)->with("message", "An error occurred. Please try again!");
        }
    }

    public function authorize(): bool
    {
        return Gate::allows('review:create');
    }


    /**
     * Returns an array of validation rules for the `content`.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'min:50'],
        ];
    }

    /**
     * Returns the validation error bag for the 'createReview' validation.
     *
     * @return string The validation error bag name.
     */
    public function getValidationErrorBag(): string
    {
        return 'createReview';
    }

    /**
     * Returns an array of validation messages for the 'content'.
     *
     * @return array An associative array where the keys are the validation rules and the values are the corresponding error messages.
     */
    public function getValidationMessages(): array
    {
        return [
            'content.required' => 'Please enter a review.',
            'content.min' => 'Your review is not long enough.',
        ];
    }
}
