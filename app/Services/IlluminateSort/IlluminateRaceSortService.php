<?php

declare(strict_types=1);


namespace App\Services\IlluminateSort;

class IlluminateRaceSortService
{
    public const string DEFAULT_SORT = self::SORT_DATE_DESC;

    public const string SORT_NAME_ASC = 'name:asc';
    public const string SORT_NAME_DESC = 'name:desc';
    public const string SORT_DATE_ASC = 'date:asc';
    public const string SORT_DATE_DESC = 'date:desc';
    public const string SORT_LOCATION_ASC = 'location:asc';
    public const string SORT_LOCATION_DESC = 'location:desc';
    public const string SORT_DISTANCE_ASC = 'distance:asc';
    public const string SORT_DISTANCE_DESC = 'distance:desc';
    public const string SORT_RESULTS_COUNT_ASC = 'results_count:asc';
    public const string SORT_RESULTS_COUNT_DESC = 'results_count:desc';
    public const string SORT_CREATED_AT_ASC = 'created_at:asc';
    public const string SORT_CREATED_AT_DESC = 'created_at:desc';

    public const array AVAILABLE_SORTS = [
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
