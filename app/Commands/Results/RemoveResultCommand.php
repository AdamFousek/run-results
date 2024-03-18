<?php

declare(strict_types=1);


namespace App\Commands\Results;

use App\Repositories\ResultRepositoryInterface;

readonly class RemoveResultCommand
{
    public function __construct(
        private ResultRepositoryInterface $repository
    ) {
    }

    /**
     * @param int[] $ids
     * @return void
     */
    public function handle(array $ids): void
    {
        $this->repository->delete($ids);
    }
}
