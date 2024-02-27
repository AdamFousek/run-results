<?php

declare(strict_types=1);


namespace App\Http\Transformers\Meilisearch;

use App\Models\Meilisearch\Runner;
use Illuminate\Support\Collection;

class RunnerListTransformer
{
    /**
     * @param Collection<?Runner> $collection
     * @return array<array{
     *     id: int,
     *     firstName: string,
     *     lastName: string,
     *     club: string|null,
     *     city: string|null,
     *     year: int,
     *     gender: string|null,
     *     resultsCount: int,
     * }>
     */
    public function transform(Collection $collection): array
    {
        $result = [];
        foreach ($collection as $runner) {
            if (!$runner instanceof Runner) {
                continue;
            }

            $result[] = [
                'id' => $runner->getId(),
                'firstName' => $runner->getFirstName(),
                'lastName' => $runner->getLastName(),
                'club' => $runner->getClub(),
                'city' => $runner->getCity(),
                'year' => $runner->getYear(),
                'gender' => $runner->getGender(),
                'resultsCount' => $runner->getResultsCount(),
            ];
        }

        return $result;
    }
}
