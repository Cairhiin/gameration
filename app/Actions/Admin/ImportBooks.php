<?php

namespace App\Actions\Admin;

use App\Jobs\importCSV;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ImportBooks
{
    use AsAction;

    public function handle(): bool
    {
        $newestImportedFileName = GetNewestFileInDirectory::run('data\books');

        if (!$newestImportedFileName) {
            return false;
        }

        if (DB::table('data_imports')->where('file_name', $newestImportedFileName)->exists()) {
            return false;
        }

        DB::table('data_imports')->insert([
            'file_name' => $newestImportedFileName,
            'created_at' => now(),
        ]);

        importCSV::dispatch($newestImportedFileName, 'books');

        return true;
    }
}
