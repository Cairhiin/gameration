<?php

namespace App\Actions\Games\Reviews;

use App\Models\Game;
use App\Models\User;
use App\Models\GameUser;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(ActionRequest $request, GameUser $review): bool
    {
        $approved = $review->approved;

        if (!$approved) {
            $approved = User::find(auth()->id())->isAdmin() || User::find(auth()->id())->isModerator() ? true : false;
        }

        return $review->update([
            'content' => $request->content,
            'approved' => $approved
        ]);
    }

    public function asController(ActionRequest $request, Game $game, GameUser $review): RedirectResponse
    {
        $success = $this->handle($request, $review);

        if ($success && !$review->approved) {
            return Redirect::route("games.show", $game->id)->with("message", "Review" . SystemMessage::AWAIT_APPROVAL);
        } elseif ($success) {
            return Redirect::route("games.show", $game->id)->with("message", "Review" . SystemMessage::UPDATE_SUCCESS);
        } else {
            return Redirect::route("games.show", $game->id)->with("message", "Review" . SystemMessage::UPDATE_FAILURE);
        }
    }

    public function authorize(ActionRequest $request, Game $game, GameUser $review): bool
    {
        $review = $request->route()->parameter('review');

        return $review->user_id === (string) auth()->id() || Gate::allows('review:update');
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
     * Returns the validation error bag for the 'updateReview' validation.
     *
     * @return string The validation error bag name.
     */
    public function getValidationErrorBag(): string
    {
        return 'updateReview';
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
