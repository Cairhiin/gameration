<?php

namespace App\Actions\Admin;

use App\Jobs\ImportCSV;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ImportAuthors
{
    use AsAction;

    public function handle()
    {
        $newestImportedFileName = GetNewestFileInDirectory::run('data\authors');

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

        importCSV::dispatch($newestImportedFileName, 'authors');

        return true;
    }
}
