<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate\Results;

class ResultStatsCollection
{
    /**
     * @param array{time: int, year: int}|array{} $fastestTime
     * @param array{time: int, year: int}|array{} $fastestMan
     * @param array{time: int, year: int}|array{} $fastestWoman
     * @param array{time: int, year: int}|array{} $averageTime
     */
    public function __construct(
        public array $fastestTime = [],
        public array $fastestMan = [],
        public array $fastestWoman = [],
        public array $averageTime = [],
    ) {
    }
}
