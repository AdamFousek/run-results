<?php

declare(strict_types=1);


namespace App\Commands\Runner;

use App\Models\Illuminate\Runner;
use Illuminate\Support\Facades\Hash;

class UpdateRunnerHandler
{
    public function handle(UpdateRunner $command): Runner
    {
        $runner = $command->runner;
        $runner->setVisible(['day', 'month']);
        $runner->first_name = $command->firstName;
        $runner->last_name = $command->lastName;
        if (is_int($command->day) && $command->day > 0) {
            $runner->day = Hash::make((string)$command->day);
        }
        if (is_int($command->month) && $command->month > 0) {
            $runner->month = Hash::make((string)$command->month);
        }
        $runner->year = $command->year;
        $runner->city = $command->city;
        $runner->club = $command->club;
        $runner->gender = $command->gender;

        $runner->makeHidden(['day', 'month']);
        $runner->save();

        return $runner;
    }
}
