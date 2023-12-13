<?php

declare(strict_types=1);


namespace App\Http\Transformers\User;

use App\Http\Transformers\Runner\RunnerTransformer;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserListTransformer
{
    public function __construct(
        private readonly RunnerTransformer $runnerTransformer,
    ) {
    }

    /**
     * @param array<int, ?User> $users
     * @return array<array<string, string|int|float|null|array>>
     */
    public function transform(array $users): array
    {
        $transformedData = [];
        foreach ($users as $user) {
            if (!$user instanceof User) {
                continue;
            }
            $transformedData[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'runner' => $user->runner ? $this->runnerTransformer->transform($user->runner) : null,
            ];
        }

        return $transformedData;
    }
}
