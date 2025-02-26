<?php

namespace App\Actions\Publishers;

use App\Models\Publisher;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(Publisher $publisher): bool
    {
        return $publisher->delete();
    }

    public function asController(Publisher $publisher): RedirectResponse
    {
        $success = $this->handle($publisher);

        if ($success) {
            return redirect()->route('publishers.index')->with('message', 'Publisher' . SystemMessage::DELETE_SUCCESS);
        }

        return redirect()->route('publishers.index')->with('message', 'Publisher' . SystemMessage::DELETE_FAILURE);
    }

    public function authorize(): bool
    {
        return Gate::allows('publisher:delete');
    }
}
