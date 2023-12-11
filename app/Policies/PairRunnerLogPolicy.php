<?php

namespace App\Policies;

use App\Models\PairRunnerLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PairRunnerLogPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->runner === null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PairRunnerLog $pairRunnerLog): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PairRunnerLog $pairRunnerLog): bool
    {
        return $user->isAdmin();
    }
}
