<?php

declare(strict_types=1);


namespace App\Http\Transformers\Meilisearch;

use App\Casts\DistanceCast;
use App\Casts\TimeCast;
use App\Models\Meilisearch\Result\Result;
use App\Models\Meilisearch\Result\ResultRace;
use App\Models\Meilisearch\Result\ResultRunner;
use Illuminate\Support\Collection;

readonly class ResultListTransformer
{
    public function __construct(
        private DistanceCast $distanceCast,
        private TimeCast $timeCast,
    ) {
    }

    /**
     * @param Collection<?Result> $results
     * @return array{
     *     id: int,
     *     runner: array{
     *      id: int,
     *      firstName: string,
     *      lastName: string,
     *      year: int,
     *      gender: string|null
     *      },
     *     race: array{
     *      id: int,
     *      name: string,
     *      slug: string,
     *      tag: string|null,
     *      date: string|null,
     *      time: string|null,
     *      vintage: int|null,
     *      distance: string,
     *      location: string|null,
     *      surface: string|null,
     *      type: string|null,
     *      region: string|null,
     *     },
     *     category: string,
     *     categoryPosition: int|null,
     *     club: string|null,
     *     dnf: bool,
     *     dns: bool,
     *     position: int,
     *     startingNumber: int,
     *     time: string
     * }[]
     */
    public function transform(Collection $results): array
    {
        $result = [];
        foreach ($results as $item) {
            if (!$item instanceof Result) {
                continue;
            }

            $result[] = [
                'id' => $item->getId(),
                'runner' => $this->parseRunner($item->getRunner()),
                'race' => $this->parseRace($item->getRace()),
                'category' => (string)$item->getCategory(),
                'categoryPosition' => $item->getCategoryPosition(),
                'club' => $item->getClub(),
                'dnf' => $item->isDnf(),
                'dns' => $item->isDns(),
                'position' => $item->getPosition(),
                'startingNumber' => $item->getStartingNumber(),
                'time' => $this->timeCast->getFromValue((int)$item->getTime()),
            ];
        }

        return $result;
    }

    /**
     * @param ResultRunner $runner
     * @return array{
     *     id: int,
     *     firstName: string,
     *     lastName: string,
     *     year: int,
     *     gender: string|null
     * }
     */
    private function parseRunner(ResultRunner $runner): array
    {
        return [
            'id' => $runner->getId(),
            'firstName' => $runner->getFirstName(),
            'lastName' => $runner->getLastName(),
            'year' => $runner->getYear(),
            'gender' => $runner->getGender()?->value,
        ];
    }

    /**
     * @param ResultRace $race
     * @return array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     tag: string|null,
     *     date: string|null,
     *     time: string|null,
     *     vintage: int|null,
     *     distance: string,
     *     location: string|null,
     *     surface: string|null,
     *     type: string|null,
     *     region: string|null,
     * }
     */
    private function parseRace(ResultRace $race): array
    {
        return [
            'id' => $race->getId(),
            'name' => $race->getName(),
            'slug' => $race->getSlug(),
            'tag' => $race->getTag(),
            'date' => $race->getDate()?->format('j.n.Y'),
            'time' => $race->getTime(),
            'vintage' => $race->getVintage(),
            'distance' => $this->distanceCast->getValue((int)$race->getDistance()),
            'location' => $race->getLocation(),
            'surface' => $race->getSurface(),
            'type' => $race->getType(),
            'region' => $race->getRegion(),
        ];
    }
}
