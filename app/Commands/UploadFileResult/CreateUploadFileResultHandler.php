<?php

declare(strict_types=1);


namespace App\Commands\UploadFileResult;

use App\Models\Illuminate\UploadFileResult;
use Illuminate\Support\Facades\Storage;

class CreateUploadFileResultHandler
{
    public function handle(CreateUploadFileResult $command): UploadFileResult
    {
        $result = new UploadFileResult();

        $fp = file(Storage::path($command->file));
        if ($fp === false) {
            throw new \Exception('File not found');
        }

        $result->race_id = $command->race->id;
        $result->file_path = $command->file;
        $result->total_rows = count($fp);
        $result->processed_rows = 0;
        $result->failed_rows = 0;
        $result->save();

        return $result;
    }
}
