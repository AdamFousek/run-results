<?php

declare(strict_types=1);


namespace App\Commands\Race;

use App\Models\Race;

class CreateRaceCommand
{
    public function handle(CreateRace $command): Race
    {
        $race = new Race();
        $race->name = $command->name;
        $race->description = $command->description;
        $race->date = $command->date;
        $race->location = $command->location;
        $race->distance = $command->distance;
        $race->surface = $command->surface;
        $race->type = $command->type;
        $race->is_parent = $command->isParent;
        $race->parent_id = $command->parentId;

        $slug = $this->resolveSlug();
        $race->slug = $slug;

        $race->save();

        return $race;
    }
}
