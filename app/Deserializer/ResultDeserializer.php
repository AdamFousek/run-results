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
     *      id: int,
     *      name: string,
     *      slug: string,
     *      tag: string|null,
     *      date: int|null,
     *      time: string|null,
     *      vintage: int|null,
     *      distance: int|null,
     *      location: string|null,
     *      surface: string|null,
     *      type: string|null,
     *      region: string|null,
     *      },
     *     startingNumber: int,
     *     position: int,
     *     time: ?string,
     *     category: ?string,
     *     categoryPosition: ?int,
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
        $result->setTime((int)$data['time']);
        $result->setCategory($data['category']);
        $result->setCategoryPosition((int)$data['categoryPosition']);
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
        $runner->setGender(isset($data['gender']) ? RunnerGenderEnum::tryFrom($data['gender']) : null);

        return $runner;
    }

    /**
     * @param array{
     *      id: int,
     *      name: string,
     *      slug: string,
     *      tag: string|null,
     *      date: int|null,
     *      time: string|null,
     *      vintage: int|null,
     *      distance: int|null,
     *      location: string|null,
     *      surface: string|null,
     *      type: string|null,
     *      region: string|null,
     *  } $data
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
        $race->setVintage($data['vintage']);
        $race->setDistance((int)$data['distance']);
        $race->setLocation($data['location']);
        $race->setSurface($data['surface']);
        $race->setType($data['type']);
        $race->setRegion($data['region']);

        return $race;
    }
}
