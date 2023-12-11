<?php

namespace App\Http\Controllers;

use App\Commands\PairRunnerLog\PairRunnerLogCreate;
use App\Commands\PairRunnerLog\PairRunnerLogCreateHandler;
use App\Http\Requests\StorePairRunnerLogRequest;
use App\Models\PairRunnerLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class PairRunnerLogController extends Controller
{
    public function __construct(
        private readonly PairRunnerLogCreateHandler $pairRunnerLogCreateHandler,
    )
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePairRunnerLogRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $this->getUser();

        try {
            $pairRunnerLog = $this->pairRunnerLogCreateHandler->handle(new PairRunnerLogCreate(
                $user,
                $data['runnerId'],
                $data['day'],
                $data['month'],
            ));

            if ($pairRunnerLog->result === PairRunnerLog::RESULT_SUCCESS) {
                $this->withMessage(self::ALERT_SUCCESS, trans('messages.runner_pair_success'));

                return Redirect::route('profile.edit');
            }

            if ($pairRunnerLog->result === PairRunnerLog::RESULT_ERROR) {
                $userLogCount = PairRunnerLog::where('user_id', $user->id)->count();

                $this->withMessage(self::ALERT_ERROR, trans('messages.runner_pair_error', ['count' => PairRunnerLog::LIMIT - $userLogCount]));
            }

        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return Redirect::route('profile.edit');
        }

        return Redirect::route('profile.edit');
    }
}
