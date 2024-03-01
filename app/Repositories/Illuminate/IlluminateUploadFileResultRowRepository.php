<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate;

use App\Commands\UploadedFile\CreateUploadFileResultRow;
use App\Models\Illuminate\UploadFileResultRow;
use App\Repositories\IlluminateUploadFileResultRowRepositoryInterface;

class IlluminateUploadFileResultRowRepository implements IlluminateUploadFileResultRowRepositoryInterface
{
    public function create(CreateUploadFileResultRow $command): UploadFileResultRow
    {
        $uploadFileResultRow = new UploadFileResultRow();
        $uploadFileResultRow->upload_file_result_id = $command->uplodFileId;
        $uploadFileResultRow->row_number = $command->row;
        $uploadFileResultRow->data = $command->data;
        $uploadFileResultRow->error = $command->error;
        $uploadFileResultRow->save();

        return $uploadFileResultRow;
    }
}
