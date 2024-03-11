<?php

declare(strict_types=1);


namespace App\Deserializer;

use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Meilisearch\Result\Result;
use App\Models\Meilisearch\Result\ResultRace;
use App\Models\Meilisearch\Result\ResultRunner;
use Illuminate\Support\Carbon;

class ResultDeserializer
{
    /**
     * @param array{
     *     id: int,
     *     runner: array{
     *      id: int,
     *      firstName: string,
     *      lastName: string,
     *      year: int,
     *      gender?: string
     *      },
     *     race: array{
     *         id: int,
     *         name: string,
     *     slug: string,
     *     tag: ?string,
     *     date: ?int,
     *     time: ?string
     *    },
     *     startingNumber: int,
     *     position: int,
     *     time: ?string,
     *     category: ?string,
     *     categoryPosition: ?string,
     *     club: ?string,
     *     dnf: bool,
     *     dns: bool
     * } $data
     * @return Result
     */
    public function deserialize(array $data): Result
    {
        $result = new Result();
        $result->setId($data['id']);
        $result->setRunner($this->deserializeRunner($data['runner']));
        $result->setRace($this->deserializeRace($data['race']));
        $result->setStartingNumber($data['startingNumber']);
        $result->setPosition($data['position']);
        $result->setTime($data['time']);
        $result->setCategory($data['category']);
        $result->setCategoryPosition($data['categoryPosition']);
        $result->setClub($data['club']);
        $result->setDnf($data['dnf']);
        $result->setDns($data['dns']);

        return $result;
    }

    /**
     * @param array{
     *     id: int,
     *     firstName: string,
     *     lastName: string,
     *     year: int,
     *     gender?: string
     * } $data
     * @return ResultRunner
     */
    private function deserializeRunner(array $data): ResultRunner
    {
        $runner = new ResultRunner();
        $runner->setId($data['id']);
        $runner->setFirstName($data['firstName']);
        $runner->setLastName($data['lastName']);
        $runner->setYear($data['year']);
        $runner->setGender($data['gender'] ? RunnerGenderEnum::tryFrom($data['gender']) : null);

        return $runner;
    }

    /**
     * @param array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     tag: ?string,
     *     date: ?int,
     *     time: ?string
     * } $data
     * @return ResultRace
     */
    private function deserializeRace(array $data): ResultRace
    {
        $race = new ResultRace();
        $race->setId($data['id']);
        $race->setName($data['name']);
        $race->setSlug($data['slug']);
        $race->setTag($data['tag']);
        $race->setDate($data['date'] ? Carbon::createFromTimestamp($data['date']) : null);
        $race->setTime($data['time']);

        return $race;
    }
}
