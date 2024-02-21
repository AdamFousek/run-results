<?php

declare(strict_types=1);


namespace App\Deserializer;

use App\Models\Meilisearch\Runner;
use Illuminate\Support\Carbon;


class RunnerDeserializer
{
    /**
     * @param array{
     *     id: int,
     *     userId: int|null,
     *     firstName: string,
     *     lastName: string,
     *     year: int,
     *     city: string|null,
     *     club: string|null,
     *     gender: string|null,
     *     resultsCount: int,
     *     createdAt: ?int,
     *     updatedAt: ?int,
     *     upsertedAt: int
     * } $data
     * @return Runner
     */
    public function deserialize(array $data): Runner
    {
        $runner = new Runner();
        $runner->setId($data['id']);
        $runner->setUserId($data['userId']);
        $runner->setFirstName($data['firstName']);
        $runner->setLastName($data['lastName']);
        $runner->setYear($data['year']);
        $runner->setCity($data['city'] ?? null);
        $runner->setClub($data['club'] ?? null);
        $runner->setGender($data['gender'] ?? null);
        $runner->setResultsCount($data['resultsCount']);
        $runner->setCreatedAt($data['createdAt'] ? Carbon::createFromTimestamp($data['createdAt']) : null);
        $runner->setUpdatedAt($data['updatedAt'] ? Carbon::createFromTimestamp($data['updatedAt']) : null);
        $runner->setUpsertedAt(Carbon::createFromTimestamp($data['upsertedAt']));

        return $runner;
    }
}
