<?php

declare(strict_types=1);


namespace App\Models\Illuminate\Enums;

enum ResultRowEnum: int
{
    case NOT_SURE = 0;
    case ONLY_YEAR = 1;
    case DID_NOT_MATCH_DATE = 2;
    case MULTIPLE_NAMES = 3;
    case SAME_YEAR_AND_LAST_NAME = 4;

    public function trans(): string
    {
        return match ($this->value) {
            self::NOT_SURE->value => 'messages.result_row.not_sure',
            self::ONLY_YEAR->value => 'messages.result_row.only_year',
            self::DID_NOT_MATCH_DATE->value => 'messages.result_row.did_not_match_date',
            self::MULTIPLE_NAMES->value => 'messages.result_row.multiple_names',
            self::SAME_YEAR_AND_LAST_NAME->value => 'messages.result_row.same_year_and_last_name',
        };
    }
}
