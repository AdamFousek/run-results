<?php

declare(strict_types=1);


namespace App\Commands\Race;

use App\Models\Race;

class UpdateRaceCommand
{
    public function handle(UpdateRace $command): Race
    {
        $race = $command->race;

        $race->name = $command->name;
        $race->description = $command->description;
        $race->date = $command->date;
        $race->location = $command->location;
        $race->distance = $command->distance;
        $race->surface = $command->surface;
        $race->type = $command->type;
        $race->is_parent = $command->isParent;
        $race->parent_id = $command->parentId;

        $race->save();

        return $race;
    }
}
