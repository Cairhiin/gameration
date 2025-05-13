<?php

namespace App\Actions\Persons;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Person;
use Illuminate\Support\Facades\Gate;
use Lorisleiva\Actions\Concerns\AsAction;

class Show
{
    use AsAction;

    public function handle(Person $person)
    {
        $avg_rating = 0.0;
        $median_rating = 0.0;
        $ratings = 0;
        $rating_count = 0;

        foreach ($person->books as $book) {
            $avg_rating += $book->getAvgRatingAttribute();
            $median_rating += $book->getMedianRatingAttribute();
            $ratings += $book->getRatingCountAttribute();
            $rating_count++;
        }

        return [
            'avg_rating' => $rating_count ? $avg_rating / $rating_count : 0.0,
            'median_rating' => $rating_count ? $median_rating / $rating_count : 0.0,
            'rating_count' => $ratings
        ];
    }

    public function asController(Person $person): Response
    {
        $data = $this->handle($person);

        return Inertia::render('Persons/Show', [
            'person' => $person->load(['books', 'series']),
            'avg_rating' => $data['avg_rating'],
            'median_rating' => $data['median_rating'],
            'rating_count' => $data['rating_count']
        ]);
    }

    public function authorize(): bool
    {
        return Gate::allows('person:view');
    }
}
