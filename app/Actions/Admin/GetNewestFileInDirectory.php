<?php

namespace App\Actions\Admin;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class GetNewestFileInDirectory
{
    use AsAction;

    public function handle(string $directory): ?string
    {
        $disk = Storage::disk('public');

        if (!$disk->exists($directory)) {
            return null;
        }

        $files = $disk->files($directory);

        if (empty($files)) {
            return null;
        }

        $fileData = collect();
        foreach ($files as $file) {
            $fileData->push([
                'file' => $file,
                'date' => $disk->lastModified($file)
            ]);
        }

        $newest = $fileData->sortByDesc('date')->first();

        return $newest['file'];
    }
}
