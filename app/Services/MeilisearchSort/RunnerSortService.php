<?php

declare(strict_types=1);


namespace App\Services\MeilisearchSort;

class RunnerSortService
{
    public const DEFAULT_SORT = self::SORT_NAME_ASC;

    public const SORT_NAME_ASC = 'lastName:asc';
    public const SORT_NAME_DESC = 'lastName:desc';
    public const SORT_YEAR_ASC = 'year:asc';
    public const SORT_YEAR_DESC = 'year:desc';
    public const SORT_CLUB_ASC = 'club:asc';
    public const SORT_CLUB_DESC = 'club:desc';
    public const SORT_CITY_ASC = 'city:asc';
    public const SORT_CITY_DESC = 'city:desc';
    public const SORT_RESULTS_COUNT_ASC = 'resultsCount:asc';
    public const SORT_RESULTS_COUNT_DESC = 'resultsCount:desc';

    public const AVAILABLE_SORTS = [
        self::SORT_NAME_ASC => 'sort.name_asc',
        self::SORT_NAME_DESC => 'sort.name_desc',
        self::SORT_YEAR_ASC => 'sort.date_asc',
        self::SORT_YEAR_DESC => 'sort.date_desc',
        self::SORT_CLUB_ASC => 'sort.location_asc',
        self::SORT_CLUB_DESC => 'sort.location_desc',
        self::SORT_CITY_ASC => 'sort.distance_asc',
        self::SORT_CITY_DESC => 'sort.distance_desc',
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
