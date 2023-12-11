<?php

declare(strict_types=1);


namespace App\Commands\PairRunnerLog;

use App\Models\PairRunnerLog;
use App\Models\Runner;
use Exception;
use Illuminate\Support\Facades\Hash;

class PairRunnerLogCreateHandler
{
    /**
     * @throws Exception
     */
    public function handle(PairRunnerLogCreate $command): PairRunnerLog
    {
        $runner = Runner::whereId($command->runnerId)->first();
        $user = $command->user;

        if ($runner === null) {
            throw new Exception(trans('runner_not_found'));
        }

        $runner->setVisible(['day', 'month']);
        $pairRunnerLog = new PairRunnerLog();
        $pairRunnerLog->runner_id = $runner->id;
        $pairRunnerLog->user_id = $user->id;

        if ($runner->day === null || $runner->month === null) {
            $pairRunnerLog->error = trans('messages.runner_pair_runner_not_day_or_month');
            $pairRunnerLog->result = PairRunnerLog::RESULT_NEED_ATTENTION;
            $pairRunnerLog->save();

            return $pairRunnerLog;
        }

        if (!Hash::check((string)$command->day, $runner->day)) {
            $pairRunnerLog->error = trans('messages.runner_pair_day_incorrect');
            $pairRunnerLog->result = PairRunnerLog::RESULT_ERROR;
            $pairRunnerLog->save();

            return $pairRunnerLog;
        }

        if (!Hash::check((string)$command->month, $runner->month)) {
            $pairRunnerLog->error = trans('messages.runner_pair_month_incorrect');
            $pairRunnerLog->result = PairRunnerLog::RESULT_ERROR;
            $pairRunnerLog->save();

            return $pairRunnerLog;
        }

        $pairRunnerLog->result = PairRunnerLog::RESULT_SUCCESS;
        $pairRunnerLog->save();

        return $pairRunnerLog;
    }
}
