<?php

declare(strict_types=1);


namespace App\Commands\Race;

use App\Models\Race;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CreateRaceCommand
{
    public function handle(CreateRace $command): Race
    {
        $date = null;
        if ($command->date !== '') {
            $date = Carbon::createFromFormat('Y-m-d', $command->date);
        }
        $time = null;
        if ($command->time !== '') {
            $time = (new Carbon())->setTimeFromTimeString($command->time);
        }
        $race = new Race();
        $race->name = $command->name;
        $race->description = $command->description;
        $race->date = $date;
        $race->time = $time;
        $race->location = $command->location;
        $race->distance = $command->distance > 0 ? $command->distance : null;
        $race->surface = $command->surface;
        $race->type = $command->type;
        $race->is_parent = $command->isParent;
        $race->parent_id = $command->parentId;

        $slug = $this->resolveSlug(Str::slug($command->name));
        $race->slug = $slug;

        $race->save();

        return $race;
    }

    private function resolveSlug(string $slug, int $try = 0): string
    {
        $newSlug = $slug;
        if ($try > 0) {
            $newSlug = $slug . '-' . $try;
        }

        if (Race::withTrashed()->whereSlug($newSlug)->exists()) {
            $try++;
            return $this->resolveSlug($slug, $try);
        }

        return $newSlug;
    }
}
