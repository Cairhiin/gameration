<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\Person;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uniqueFor = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct(public $chunk, public $directory)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->chunk->each(function (array $row) {
            if ($this->directory === 'books') {
                $this->importBook($row);
            } elseif ($this->directory === 'authors') {
                $this->importAuthor($row);
            }
        });
    }

    private function importBook(array $row): void
    {
        $book = json_decode($row[sizeof($row) - 1]);

        if (empty($author->name)) {
            Log::error('Author name is empty', [
                'row' => $row,
            ]);
            return;
        }

        Book::updateOrCreate([
            'title' => $book->title,
        ], [
            'title' => $book->title,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function importAuthor(array $row): void
    {
        if (!$row) return;

        $author = json_decode($row[sizeof($row) - 1]);
        $author_id = sizeof($row) > 1 ? explode('/', $row[1]) : null;
        $author_id = $author_id ? $author_id[sizeof($author_id) - 1] : null;

        if (empty($author->name)) {
            Log::error('Author name is empty', [
                'row' => $row,
            ]);

            return;
        }

        Person::updateOrCreate([
            'name' => $author->name,
        ], [
            'name' => $author->name,
            'type' => 'author',
            'OpenLibrary_id' => $author_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Returns a unique identifier for the job.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return Str::uuid()->toString();
    }
}
