<?php

declare(strict_types=1);


namespace App\Repositories;

interface IlluminateResultRepositoryInterface
{
    /**
     * @param string $raceTag
     * @return ?array{time: int, year: int}
     */
    public function getFastestTimeByRaceIds(string $raceTag): ?array;

    /**
     * @param string $raceTag
     * @return ?array{time: int, year: int}
     */
    public function getFastestManByRaceIds(string $raceTag): ?array;

    /**
     * @param string $raceTag
     * @return ?array{time: int, year: int}
     */
    public function getFastestWomanByRaceIds(string $raceTag): ?array;

    /**
     * @param string $raceTag
     * @return ?array{time: int}
     */
    public function getAverageTimeByRaceIds(string $raceTag): ?array;
}
