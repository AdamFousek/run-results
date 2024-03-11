<?php

declare(strict_types=1);


namespace App\Deserializer;

use App\Casts\TimeCast;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Meilisearch\Result\ResultRace;
use App\Models\Meilisearch\Result\ResultRunner;
use App\Models\Meilisearch\Result\TopResult;
use Illuminate\Support\Carbon;

class TopResultDeserializer
{
    public function __construct(
        private readonly TimeCast $timeCast,
    ) {
    }

    /**
     * @param array{
     *     id: int,
     *     topPosition: int,
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
     *     time: int
     * } $data
     * @return TopResult
     */
    public function deserialize(array $data): TopResult
    {
        $result = new TopResult();
        $result->setId($data['id']);
        $result->setTopPosition($data['topPosition']);
        $result->setRunner($this->deserializeRunner($data['runner']));
        $result->setRace($this->deserializeRace($data['race']));
        $result->setTime($this->timeCast->getFromValue($data['time']));

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
