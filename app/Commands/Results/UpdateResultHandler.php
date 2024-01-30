<?php

declare(strict_types=1);


namespace App\Commands\Results;

class UpdateResultHandler
{
    public function handle(UpdateResult $command): \App\Models\Result
    {
        $result = $command->result;

        $result->runner_id = $command->runnerId;
        $result->race_id = $command->raceId;
        $result->position = $command->position;
        $result->starting_number = $command->startingNumber;
        $result->time = $command->time;
        $result->category_position = $command->categoryPosition;
        $result->category = $command->category;
        $result->DNS = $command->dns ? 1 : 0;
        $result->DNF = $command->dnf ? 1 : 0;

        $result->save();

        return $result;

    }
}
