<?php

namespace App\Actions\Achievements;

use App\Models\Achievement;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Update
{
    use AsAction;

    public function handle(ActionRequest $request, Achievement $achievement)
    {
        try {
            $path = $request->file('image') ? $request->file('image')->store('images', 'public') : $achievement->image;

            DB::beginTransaction();

            $achievement->update(
                [
                    'title' => $request['title'],
                    'description' => $request['description'],
                    'points' => $request['points'],
                    'image' => $path,
                ]
            );

            DB::commit();

            return $achievement;
        } catch (\Exception $e) {
            DB::rollBack();

            return null;
        }
    }

    public function asController(ActionRequest $request, Achievement $achievement)
    {
        $success = $this->handle($request, $achievement);

        if (!$success) {
            return redirect()->route('achievements.show', $achievement->id)->with('message', 'Achievement' . SystemMessage::UPDATE_FAILURE);
        }

        return redirect()->route('achievements.show', $achievement->id)->with('message', 'Achievement' . SystemMessage::UPDATE_SUCCESS);
    }

    public function authorize(): bool
    {
        return Gate::allows('achievement:update');
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:3', 'unique:achievements,title,' . request()->route('achievement')->id],
            'description' => ['required', 'string', 'min:25'],
            'points' => ['required', 'integer'],
            'image' => ['nullable', 'mimes:jpg,bmp,png', 'max:2048']
        ];
    }

    public function getValidationErrorBag()
    {
        return 'updateAchievement';
    }

    public function getValidationMessages()
    {
        return [
            'title.required' => 'Looks like you forgot to give the achievement a title.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title must be less than 255 characters.',
            'title.min' => 'The title must be at least 3 characters.',
            'description.required' => 'Looks like you forgot to give the achievement a description.',
            'description.string' => 'The description must be a string.',
            'description.min' => 'The description must be at least 25 characters.',
            'points.required' => 'Looks like you forgot to give the achievement points.',
            'points.integer' => 'The points must be an integer.',
            'image.mimes' => 'Unsupported image format. Supported formats are: jpg, bmp, png.',
            'image.max' => 'The image size must be less than 2MB.',
        ];
    }
}
