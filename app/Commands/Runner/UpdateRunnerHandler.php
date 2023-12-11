<?php

declare(strict_types=1);


namespace App\Commands\Runner;

use App\Models\Runner;
use Illuminate\Support\Facades\Hash;

class UpdateRunnerHandler
{
    public function handle(UpdateRunner $command): Runner
    {
        $runner = $command->runner;
        $runner->setVisible(['day', 'month']);
        $runner->first_name = $command->firstName;
        $runner->last_name = $command->lastName;
        $day = $command->day === null ? null : (string)$command->day;
        $month = $command->month === null ? null : (string)$command->month;
        if ($day && !Hash::check($day, (string)$runner->day)) {
            $runner->day = Hash::make($day);
        }
        if ($month && !Hash::check($month, (string)$runner->month)) {
            $runner->month = Hash::make($month);
        }
        $runner->year = $command->year;
        $runner->city = $command->city;
        $runner->club = $command->club;

        $runner->save();

        return $runner;
    }
}
