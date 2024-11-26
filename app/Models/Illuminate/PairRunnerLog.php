<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PairRunnerLog extends IlluminateModel
{
    public const RESULT_SUCCESS = 10;
    public const RESULT_ERROR = 20;
    public const RESULT_NEED_ATTENTION = 30;

    public const LIMIT = 3;

    protected $fillable = [
        'runner_id',
        'user_id',
        'result',
        'error',
    ];

    public function runner(): BelongsTo
    {
        return $this->belongsTo(Runner::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
