<?php

namespace App\Actions\Profile\Friends\Messages;

use App\Jobs\SendNewMessageNotification;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageReceived;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\ActionRequest;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(ActionRequest $request, User $user): ?Message
    {
        try {
            DB::beginTransaction();

            $message = Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $user->id,
                'subject' => $request->subject,
                'body' => $request->body
            ]);

            DB::commit();

            SendNewMessageNotification::dispatch($user, $message)->delay(now()->addSeconds(10));

            return $message;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(ActionRequest $request, User $user): RedirectResponse
    {
        $message = $this->handle($request, $user);

        if ($message) {
            return Redirect::route("profile.friends.index")->with("message", "Message has been sent.");
        } else {
            return Redirect::route("profile.friends.index")->with("message", "Failed to send message!");
        }
    }

    /**
     * Returns an array of validation rules for the `subject` and `body` field.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'subject' => ['required', 'min:3'],
            'body' => ['required', 'min:3'],
        ];
    }

    /**
     * Returns the validation error bag for the 'addFriend' validation.
     *
     * @return string The validation error bag name.
     */
    public function getValidationErrorBag(): string
    {
        return 'addMessage';
    }

    /**
     * Returns an array of validation messages for the `subject` and `body` field.
     *
     * @return array An associative array where the keys are the validation rules and the values are the corresponding error messages.
     */
    public function getValidationMessages(): array
    {
        return [
            'subject.required' => 'Looks like you forgot to add a subject.',
            'body.required' => 'Looks like you forgot to add a message body.',
        ];
    }
}
