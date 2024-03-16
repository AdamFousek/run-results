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

    public function handle(int $id): void
    {
        $this->repository->delete($id);
    }
}
