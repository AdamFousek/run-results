<?php

declare(strict_types=1);


namespace App\Models\Enums;

enum ResultRowEnum: int
{
    case NOT_SURE = 0;
    case ONLY_YEAR = 1;
    case DID_NOT_MATCH_DATE = 2;

    public function trans(): string
    {
        return match ($this->value) {
            self::NOT_SURE->value => 'messages.result_row.not_sure',
            self::ONLY_YEAR->value => 'messages.result_row.only_year',
            self::DID_NOT_MATCH_DATE->value => 'messages.result_row.did_not_match_date',
            default => '',
        };
    }
}
