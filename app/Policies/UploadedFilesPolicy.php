<?php

namespace App\Policies;

use App\Models\Illuminate\UploadedFiles;
use App\Models\Illuminate\User;
use Illuminate\Auth\Access\Response;

class UploadedFilesPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UploadedFiles $uploadedFiles): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UploadedFiles $uploadedFiles): bool
    {
        return $user->isAdmin();
    }
}
