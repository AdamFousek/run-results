<?php

declare(strict_types=1);


namespace App\Commands\Results;

use App\Models\Result;

class CreateResultCommand
{
    public function handle(CreateResult $command): Result
    {
        $result = new Result();

        $result->runner_id = $command->runnerId;
        $result->race_id = $command->raceId;
        $result->position = $command->position;
        $result->starting_number = $command->startingNumber;
        $result->time = $command->time;
        $result->category_position = $command->categoryPosition;
        $result->category = $command->category;

        $result->save();

        return $result;
    }
}
