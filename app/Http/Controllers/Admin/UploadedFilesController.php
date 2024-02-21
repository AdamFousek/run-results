<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Illuminate\UploadedFiles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadedFilesController extends AdminController
{

    public function togglePublicity(UploadedFiles $uploadedFiles): RedirectResponse
    {
        $this->authorize('update', $uploadedFiles);

        $uploadedFiles->is_public = !$uploadedFiles->is_public;
        $uploadedFiles->save();

        $this->withMessage(self::ALERT_SUCCESS, trans('messages.file_updated_successfuly'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UploadedFiles $uploadedFiles): RedirectResponse
    {
        $this->authorize('delete', $uploadedFiles);

        try {
            Storage::delete($uploadedFiles->file_path);
            $uploadedFiles->delete();

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.file_removed_successfuly'));

            return redirect()->back();
        } catch (\Exception $e) {
            $this->withMessage(self::ALERT_ERROR, $e->getMessage());
            return redirect()->back();
        }
    }
}
