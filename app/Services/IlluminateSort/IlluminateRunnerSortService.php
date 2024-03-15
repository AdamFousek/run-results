<?php

declare(strict_types=1);


namespace App\Services\IlluminateSort;

class IlluminateRunnerSortService
{
    public const string DEFAULT_SORT = self::SORT_NAME_ASC;

    public const string SORT_NAME_ASC = 'last_name:asc';
    public const string SORT_NAME_DESC = 'last_name:desc';
    public const string SORT_YEAR_ASC = 'year:asc';
    public const string SORT_YEAR_DESC = 'year:desc';
    public const string SORT_CLUB_ASC = 'club:asc';
    public const string SORT_CLUB_DESC = 'club:desc';
    public const string SORT_CITY_ASC = 'city:asc';
    public const string SORT_CITY_DESC = 'city:desc';
    public const string SORT_RESULTS_COUNT_ASC = 'results_count:asc';
    public const string SORT_RESULTS_COUNT_DESC = 'results_count:desc';
    public const string SORT_CREATED_AT_ASC = 'created_at:asc';
    public const string SORT_CREATED_AT_DESC = 'created_at:desc';

    public const array AVAILABLE_SORTS = [
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
        self::SORT_CREATED_AT_ASC => 'sort.createdAt_asc',
        self::SORT_CREATED_AT_DESC => 'sort.createdAt_desc',
    ];

    public function resolveSort(string $sort, string $default = self::DEFAULT_SORT): string
    {
        if (!array_key_exists($sort, self::AVAILABLE_SORTS)) {
            $sort = $default;
        }

        return $sort;
    }
}
