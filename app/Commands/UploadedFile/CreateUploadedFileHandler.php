<?php

declare(strict_types=1);


namespace App\Commands\UploadedFile;

use App\Models\Illuminate\UploadedFiles;

class CreateUploadedFileHandler
{
    public function handle(CreateUploadedFile $command): UploadedFiles
    {
        $uploadedFile = new UploadedFiles();
        $uploadedFile->file_path = $command->path;
        $uploadedFile->name = $command->name;
        $uploadedFile->is_public = $command->isPublic;
        $uploadedFile->filable_id = $command->filableId;
        $uploadedFile->filable_type = $command->filableType;

        $uploadedFile->save();

        return $uploadedFile;
    }
}
