<?php

declare(strict_types=1);


namespace App\Engines;

use App\Models\Illuminate\Race;
use App\Models\Illuminate\Runner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class MeilisearchEngine extends \Laravel\Scout\Engines\MeilisearchEngine
{

    /**
     * @param Collection<Race|Runner> $models
     * @return void
     */
    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        /** @var Race|Runner|null $model */
        $model = $models->first();
        if ($model === null) {
            return;
        }

        if ($model->getAllSearchableWith() !== []) {
            $models->load($model->getAllSearchableWith());
        }

        if ($model->getRichTextFieldsSearchable() !== []) {
            $fields = collect($model->getRichTextFieldsSearchable())
                ->map(fn ($field) => 'richText'.Str::studly($field))
                ->all();

            $models->load($fields);
        }

        $index = $this->meilisearch->index($model->searchableAs());

        if ($this->softDelete && $this->usesSoftDelete($model)) {
            $models->each->pushSoftDeleteMetadata();
        }

        $objects = $models->map(function (Race|Runner $model) {
            if ($model->getSerializer() !== null) {
                $serializer = app($model->getSerializer());

                $searchableData = $serializer->serialize($model);
            } else {
                $searchableData = $model->toSearchableArray();
            }

            if (empty($searchableData)) {
                return;
            }

            return array_merge(
                $searchableData,
                $model->scoutMetadata(),
                [$model->getScoutKeyName() => $model->getScoutKey()],
            );
        })->filter()->values()->all();

        if (! empty($objects)) {
            $index->addDocuments($objects, $model->getScoutKeyName());
        }
    }
}
