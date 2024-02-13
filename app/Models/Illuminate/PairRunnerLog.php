<?php

namespace App\Models\Illuminate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Illuminate\PairRunnerLog
 *
 * @property int $id
 * @property int $runner_id
 * @property int $user_id
 * @property int $result
 * @property string $error
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Illuminate\Runner $runner
 * @property-read \App\Models\Illuminate\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog whereRunnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PairRunnerLog whereUserId($value)
 * @mixin \Eloquent
 */
class PairRunnerLog extends Model
{
    use HasFactory;

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
