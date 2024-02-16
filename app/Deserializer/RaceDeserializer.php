<?php

declare(strict_types=1);


namespace App\Deserializer;

use App\Models\Meilisearch\Files;
use App\Models\Meilisearch\ParentRace;
use App\Models\Meilisearch\Race;
use Illuminate\Support\Carbon;

class RaceDeserializer
{
    /**
     * @param array{
     *      id: int,
     *      name: string,
     *      slug: string,
     *      description: string,
     *      date: int|null,
     *      time: string|null,
     *      location: string,
     *      region: string,
     *      distance: int,
     *      vintage: int,
     *      surface: string,
     *      type: string,
     *      tag: string,
     *      isParent: bool,
     *      parent: array{id: int, name: string, slug: string}|null,
     *      _geo: array{lat: float, lng: float},
     *      files: array{
     *      id: int,
     *      name: string,
     *      url: string,
     *      isPublic: bool
     *      }[],
     *      runnerCount: int,
     *      createdAt: int|null,
     *      updatedAt: int|null,
     *      upsertedAt: int
     *  } $data
     * @return void
     */
    public function deserialize(array $data): Race
    {
        $race = new Race();
        $race->setId($data['id']);
        $race->setName($data['name']);
        $race->setSlug($data['slug']);
        $race->setDescription($data['description']);
        $race->setDate($data['date'] !== null ? Carbon::createFromTimestamp($data['date']) : null);
        $race->setTime($data['time'] ?? null);
        $race->setLocation($data['location'] ?? '');
        $race->setRegion($data['region'] ?? '');
        $race->setDistance((int)($data['distance'] ?? 0));
        $race->setVintage($data['vintage'] ?? null);
        $race->setSurface($data['surface']);
        $race->setType($data['type']);
        $race->setTag($data['tag']);
        $race->setIsParent((bool)$data['isParent']);
        $race->setLatitude($data['_geo']['lat'] ?? null);
        $race->setLongitude($data['_geo']['lng'] ?? null);
        $race->setResultsCount($data['runnerCount']);
        $race->setCreatedAt($data['createdAt'] !== null ? Carbon::createFromTimestamp($data['createdAt']) : null);
        $race->setUpdatedAt($data['updatedAt'] !== null ? Carbon::createFromTimestamp($data['updatedAt']) : null);
        $race->setUpsertedAt(Carbon::createFromTimestamp($data['upsertedAt']));
        $race->setParent($this->resolveParent($data));
        $race->setFiles($this->resolveFiles($data));

        return $race;
    }

    private function resolveParent(array $data): ?ParentRace
    {
        if ($data['parent'] === null) {
            return null;
        }

        $parent = new ParentRace();
        $parent->setId($data['parent']['id']);
        $parent->setName($data['parent']['name']);
        $parent->setSlug($data['parent']['slug']);
        return $parent;
    }

    /**
     * @param Files[]|array{} $data
     * @return array
     */
    private function resolveFiles(array $data): array
    {
        return array_map(function (array $f) {
            $file = new Files();
            $file->setId($f['id']);
            $file->setName($f['name']);
            $file->setUrl($f['url']);
            $file->setIsPublic((bool)$f['isPublic']);
            return $file;
        }, $data['files'], []);
    }
}
