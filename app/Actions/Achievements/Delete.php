<?php

namespace App\Actions\Achievements;

use App\Models\Achievement;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\Log;
use PHPUnit\Event\Telemetry\System;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Delete
{
    use AsAction;

    public function handle(Achievement $achievement)
    {
        try {
            $achievement->delete();

            return true;
        } catch (\Exception $e) {
            Log::error($e);

            return false;
        }
    }

    public function asController(Achievement $achievement)
    {
        $success = $this->handle($achievement);

        if ($success) {
            return redirect()->route('achievements.index')->with('message', 'Achievement' . SystemMessage::DELETE_SUCCESS);
        }

        return redirect()->route('achievements.index')->with('message', 'Achievement' . SystemMessage::DELETE_FAILURE);
    }

    public function authorize()
    {
        return Gate::allows('achievement:delete');
    }
}
