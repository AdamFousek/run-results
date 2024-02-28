<?php

declare(strict_types=1);


namespace App\Http\Transformers\User;

use App\Http\Transformers\Runner\RunnerTransformer;
use App\Models\Illuminate\User;
use Illuminate\Database\Eloquent\Collection;

class UserListTransformer
{
    public function __construct(
        private readonly RunnerTransformer $runnerTransformer,
    ) {
    }

    /**
     * @param array<int, ?User> $users
     * @return array<array{
     *     id: int,
     *      username: string,
     *      email: string,
     *      runner: array{
     *          id: int,
     *          firstName: string,
     *          lastName: string,
     *          year: int,
     *          city: string|null,
     *          club: string|null,
     *          results_count: int
     *      }|null
     *  }>
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
