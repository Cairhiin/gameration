<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $fileName, private string $type)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        SimpleExcelReader::create(storage_path('app/public/' . $this->fileName))
            ->useDelimiter("\t")
            ->noHeaderRow()
            ->getRows()
            ->chunk(5000)
            ->each(
                fn($chunk) => ImportChunk::dispatch($chunk, $this->type)
            );
    }
}
