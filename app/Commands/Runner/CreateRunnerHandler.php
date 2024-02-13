<?php

declare(strict_types=1);


namespace App\Commands\Runner;

use App\Models\Illuminate\Runner;
use Illuminate\Support\Facades\Hash;

class CreateRunnerHandler
{
    public function handle(CreateRunner $command): Runner
    {
        $runner = new Runner();
        $runner->first_name = $command->firstName;
        $runner->last_name = $command->lastName;
        $runner->day = $command->day ? Hash::make((string)$command->day) : null;
        $runner->month = $command->month ? Hash::make((string)$command->month) : null;
        $runner->year = $command->year;
        $runner->city = $command->city;
        $runner->club = $command->club;

        $runner->save();

        return $runner;
    }
}
