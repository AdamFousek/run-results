<?php

declare(strict_types=1);


namespace App\Services;

class ResultSortService
{
    public const string DEFAULT_SORT = self::SORT_RACE_DATE_DESC;

    public const string SORT_RACE_NAME_ASC = 'race.name:asc';
    public const string SORT_RACE_NAME_DESC = 'race.name:desc';
    public const string SORT_RACE_DATE_ASC = 'race.date:asc';
    public const string SORT_RACE_DATE_DESC = 'race.date:desc';
    public const string SORT_RACE_LOCATION_ASC = 'race.location:asc';
    public const string SORT_RACE_LOCATION_DESC = 'race.location:desc';
    public const string SORT_RACE_DISTANCE_ASC = 'race.distance:asc';
    public const string SORT_RACE_DISTANCE_DESC = 'race.distance:desc';
    public const string SORT_CATEGORY_POSITION_ASC = 'categoryPosition:asc';
    public const string SORT_CATEGORY_POSITION_DESC = 'categoryPosition:desc';
    public const string SORT_TIME_ASC = 'time:asc';
    public const string SORT_TIME_DESC = 'time:desc';
    public const string SORT_POSITION_ASC = 'position:asc';
    public const string SORT_POSITION_DESC = 'position:desc';

    /**
     * @var array<string, string>
     */
    public const array AVAILABLE_SORTS = [
        self::SORT_RACE_NAME_ASC => 'sort.name_asc',
        self::SORT_RACE_NAME_DESC => 'sort.name_desc',
        self::SORT_RACE_DATE_ASC => 'sort.date_asc',
        self::SORT_RACE_DATE_DESC => 'sort.date_desc',
        self::SORT_RACE_LOCATION_ASC => 'sort.location_asc',
        self::SORT_RACE_LOCATION_DESC => 'sort.location_desc',
        self::SORT_RACE_DISTANCE_ASC => 'sort.distance_asc',
        self::SORT_RACE_DISTANCE_DESC => 'sort.distance_desc',
        self::SORT_CATEGORY_POSITION_ASC => 'sort.resultsCount_asc',
        self::SORT_CATEGORY_POSITION_DESC => 'sort.resultsCount_desc',
        self::SORT_TIME_ASC => 'sort.time_asc',
        self::SORT_TIME_DESC => 'sort.time_desc',
        self::SORT_POSITION_ASC => 'sort.position_asc',
        self::SORT_POSITION_DESC => 'sort.position_desc',
    ];

    public function resolveSort(string $sort, string $default = self::DEFAULT_SORT): string
    {
        if (!array_key_exists($sort, self::AVAILABLE_SORTS)) {
            $sort = $default;
        }

        return $sort;
    }
}
