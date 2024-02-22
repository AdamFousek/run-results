<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate\Results;

class ResultStatsCollection
{
    /**
     * @param array{time: int, year: int} $fastestTime
     * @param array{time: int, year: int} $fastestMan
     * @param array{time: int, year: int} $fastestWoman
     * @param array{time: int, year: int} $averageTime
     */
    public function __construct(
        public array $fastestTime = [],
        public array $fastestMan = [],
        public array $fastestWoman = [],
        public array $averageTime = [],
    ) {
    }
}
