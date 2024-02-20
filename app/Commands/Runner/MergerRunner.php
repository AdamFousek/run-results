<?php

declare(strict_types=1);


namespace App\Commands\Runner;

use App\Models\Illuminate\Runner;

readonly class MergerRunner
{
    public function __construct(
        public Runner $source,
        public Runner $target,
    ) {
    }
}
