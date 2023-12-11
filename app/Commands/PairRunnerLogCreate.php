<?php

declare(strict_types=1);


namespace App\Commands;

use App\Models\User;

class PairRunnerLogCreate
{
    public function __construct(
        public readonly User $user,
        public readonly int $runnerId,
        public readonly int $day,
        public readonly int $month,
    ) {
    }
}
