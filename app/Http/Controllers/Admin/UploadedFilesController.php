<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Illuminate\UploadedFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadedFilesController extends AdminController
{

    public function togglePublicity(UploadedFiles $uploadedFiles): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', $uploadedFiles);

        $uploadedFiles->is_public = !$uploadedFiles->is_public;
        $uploadedFiles->save();

        $this->withMessage(self::ALERT_SUCCESS, 'file_updated_successfuly');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UploadedFiles $uploadedFiles)
    {
        $this->authorize('delete', $uploadedFiles);

        Storage::delete($uploadedFiles->file_path);
    }
}
