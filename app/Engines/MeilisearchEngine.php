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
    public function update($models): void
    {
        if ($models->isEmpty()) {
            return;
        }

        /** @var Race|Runner $model */
        $model = $models->first();

        $searchableData = $model->getAllSearchableWith();
        if ($searchableData !== []) {
            $models->load($searchableData);
        }

        $fields = collect($model->getRichTextFieldsSearchable())
            ->map(fn ($field) => 'richText'.Str::studly($field))
            ->all();
        if ($fields !== []) {
            $models->load($fields);
        }

        $index = $this->meilisearch->index($model->searchableAs());

        if ($this->softDelete && $this->usesSoftDelete($model)) {
            foreach ($models as $model) {
                $model->pushSoftDeleteMetadata();
            }
        }

        $objects = collect();

        foreach ($models as $upsertedModel) {
            if ($upsertedModel->getSerializer() !== null) {
                $serializer = app($upsertedModel->getSerializer());

                $searchableData = $serializer->serialize($upsertedModel);
            } else {
                $searchableData = $upsertedModel->toSearchableArray();
            }

            if (empty($searchableData)) {
                continue;
            }

            $objects->add(array_merge(
                $searchableData,
                $model->scoutMetadata(),
                [$model->getScoutKeyName() => $model->getScoutKey()],
            ));
        }

        if ($objects->isNotEmpty()) {
            $index->addDocuments($objects->values()->all(), $model->getScoutKeyName());
        }
    }
}
