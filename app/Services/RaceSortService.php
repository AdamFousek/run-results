<?php

declare(strict_types=1);


namespace App\Services;


class RaceSortService
{
    public const DEFAULT_SORT = self::SORT_DATE_DESC;

    public const SORT_NAME_ASC = 'name:asc';
    public const SORT_NAME_DESC = 'name:desc';
    public const SORT_DATE_ASC = 'date:asc';
    public const SORT_DATE_DESC = 'date:desc';
    public const SORT_LOCATION_ASC = 'location:asc';
    public const SORT_LOCATION_DESC = 'location:desc';
    public const SORT_DISTANCE_ASC = 'distance:asc';
    public const SORT_DISTANCE_DESC = 'distance:desc';
    public const SORT_RESULTS_COUNT_ASC = 'runnerCount:asc';
    public const SORT_RESULTS_COUNT_DESC = 'runnerCount:desc';

    public const AVAILABLE_SORTS = [
        self::SORT_NAME_ASC => 'sort.name_asc',
        self::SORT_NAME_DESC => 'sort.name_desc',
        self::SORT_DATE_ASC => 'sort.date_asc',
        self::SORT_DATE_DESC => 'sort.date_desc',
        self::SORT_LOCATION_ASC => 'sort.location_asc',
        self::SORT_LOCATION_DESC => 'sort.location_desc',
        self::SORT_DISTANCE_ASC => 'sort.distance_asc',
        self::SORT_DISTANCE_DESC => 'sort.distance_desc',
        self::SORT_RESULTS_COUNT_ASC => 'sort.resultsCount_asc',
        self::SORT_RESULTS_COUNT_DESC => 'sort.resultsCount_desc',
    ];

    public function resolveSort(string $sort, string $default = self::DEFAULT_SORT): string
    {
        if (!array_key_exists($sort, self::AVAILABLE_SORTS)) {
            $sort = $default;
        }

        return $sort;
    }
}
