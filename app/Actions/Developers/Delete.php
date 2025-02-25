<?php

namespace App\Actions\Developers;

use App\Models\Developer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(Developer $developer): bool
    {
        return $developer->delete();
    }

    public function asController(Developer $developer): RedirectResponse
    {
        $success = $this->handle($developer);

        if ($success) {
            return redirect()->route('developers.index')->with('message', 'Developer deleted.');
        }

        return redirect()->route('developers.index')->with('message', 'Failed to delete developer.');
    }

    public function authorize(): bool
    {
        return Gate::allows('developer:delete');
    }
}
