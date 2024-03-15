<?php

declare(strict_types=1);


namespace App\Services\MeilisearchSort;

class ResultSortService
{
    public const string DEFAULT_SORT = self::SORT_POSITION_ASC;

    public const string SORT_RACE_NAME_ASC = 'name:asc';
    public const string SORT_RACE_NAME_DESC = 'name:desc';
    public const string SORT_RACE_DATE_ASC = 'date:asc';
    public const string SORT_RACE_DATE_DESC = 'date:desc';
    public const string SORT_RACE_LOCATION_ASC = 'location:asc';
    public const string SORT_RACE_LOCATION_DESC = 'location:desc';
    public const string SORT_RACE_DISTANCE_ASC = 'distance:asc';
    public const string SORT_RACE_DISTANCE_DESC = 'distance:desc';
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
        self::SORT_RACE_NAME_ASC => 'race.name:asc',
        self::SORT_RACE_NAME_DESC => 'race.name:desc',
        self::SORT_RACE_DATE_ASC => 'race.date:asc',
        self::SORT_RACE_DATE_DESC => 'race.date:desc',
        self::SORT_RACE_LOCATION_ASC => 'race.location:asc',
        self::SORT_RACE_LOCATION_DESC => 'race.location:desc',
        self::SORT_RACE_DISTANCE_ASC => 'race.distance:asc',
        self::SORT_RACE_DISTANCE_DESC => 'race.distance:desc',
        self::SORT_CATEGORY_POSITION_ASC => 'categoryPosition:asc',
        self::SORT_CATEGORY_POSITION_DESC => 'categoryPosition:desc',
        self::SORT_TIME_ASC => 'time:asc',
        self::SORT_TIME_DESC => 'time:desc',
        self::SORT_POSITION_ASC => 'position:asc',
        self::SORT_POSITION_DESC => 'position:desc',
    ];

    public function resolveSort(string $sort, string $default = self::DEFAULT_SORT): string
    {
        return self::AVAILABLE_SORTS[$sort] ?? self::AVAILABLE_SORTS[$default];
    }
}
