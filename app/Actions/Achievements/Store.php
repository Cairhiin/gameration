<?php

namespace App\Actions\Achievements;

use App\Models\Achievement;
use App\Enums\SystemMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Store
{
    use AsAction;

    public function handle(ActionRequest $request)
    {
        try {
            $path = $request->file('image') ? $request->file('image')->store('images', 'public') : null;

            DB::beginTransaction();

            $achievement = Achievement::create(
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

    public function asController(ActionRequest $request)
    {
        $achievement = $this->handle($request);

        if ($achievement) {
            return redirect()->route('achievements.show', $achievement->id)->with('message', 'Achievement' . SystemMessage::STORE_SUCCESS);
        }

        return redirect()->route('achievements.index')->with('message', 'Achievement' . SystemMessage::STORE_FAILURE);
    }

    public function authorize()
    {
        return Gate::allows('achievement:create');
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:3', 'unique:achievements'],
            'description' => ['required', 'string', 'min:25'],
            'points' => ['required', 'integer'],
            'image' => ['nullable', 'mimes:jpg,bmp,png', 'max:2048']
        ];
    }

    public function getValidationErrorBag()
    {
        return 'createAchievement';
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
