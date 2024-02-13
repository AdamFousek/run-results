<?php

declare(strict_types=1);


namespace App\Commands\Race;

use App\Models\Illuminate\Race;
use Illuminate\Support\Carbon;

class UpdateRaceCommand
{
    public function handle(UpdateRace $command): Race
    {
        $race = $command->race;

        $date = null;
        if ($command->date !== '') {
            $date = Carbon::createFromFormat('Y-m-d', $command->date);
        }
        $time = null;
        if ($command->time !== '') {
            $time = (new Carbon())->setTimeFromTimeString($command->time);
        }

        $race->name = $command->name;
        $race->description = $command->description;
        $race->date = $date;
        $race->time = $time;
        $race->location = $command->location;
        $race->distance = $command->distance;
        $race->surface = $command->surface;
        $race->type = $command->type;
        $race->tag = $command->tag;
        $race->vintage = $command->vintage;
        $race->region = $command->region;
        $race->latitude = $command->latitude;
        $race->longitude = $command->longitude;
        $race->is_parent = $command->isParent;
        $race->parent_id = $command->parentId;

        $race->save();

        return $race;
    }
}
