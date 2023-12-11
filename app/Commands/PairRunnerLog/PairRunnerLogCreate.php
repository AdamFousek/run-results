<?php

declare(strict_types=1);


namespace App\Commands\PairRunnerLog;

use App\Models\User;

readonly class PairRunnerLogCreate
{
    public function __construct(
        public User $user,
        public int $runnerId,
        public int $day,
        public int $month,
    ) {
    }
}
