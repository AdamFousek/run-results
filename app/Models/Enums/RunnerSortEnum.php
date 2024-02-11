<?php

declare(strict_types=1);


namespace App\Models\Enums;

enum RunnerSortEnum: string
{
    case SORT_NAME_ASC = 'name:asc';
    case SORT_NAME_DESC = 'name:desc';
    case SORT_DATE_ASC = 'year:asc';
    case SORT_DATE_DESC = 'year:desc';
    case SORT_LOCATION_ASC = 'club:asc';
    case SORT_LOCATION_DESC = 'club:desc';
    case SORT_DISTANCE_ASC = 'city:asc';
    case SORT_DISTANCE_DESC = 'city:desc';
    case SORT_RESULTS_ASC = 'resultsCount:asc';
    case SORT_RESULTS_DESC = 'resultsCount:desc';

    public function trans(): string
    {
        return match ($this) {
            self::SORT_NAME_ASC => 'sort.name_asc',
            self::SORT_NAME_DESC => 'sort.name_desc',
            self::SORT_DATE_ASC => 'sort.date_asc',
            self::SORT_DATE_DESC => 'sort.date_desc',
            self::SORT_LOCATION_ASC => 'sort.location_asc',
            self::SORT_LOCATION_DESC => 'sort.location_desc',
            self::SORT_DISTANCE_ASC => 'sort.distance_asc',
            self::SORT_DISTANCE_DESC => 'sort.distance_desc',
            self::SORT_RESULTS_ASC => 'sort.results_asc',
            self::SORT_RESULTS_DESC => 'sort.results_desc',
        };
    }
}
