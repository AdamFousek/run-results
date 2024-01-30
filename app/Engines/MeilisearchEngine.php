<?php

declare(strict_types=1);


namespace App\Engines;

use Illuminate\Support\Str;

class MeilisearchEngine extends \Laravel\Scout\Engines\MeilisearchEngine
{
    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        if (method_exists($models->first(), 'getAllSearchableWith') && is_callable([$models->first(), 'getAllSearchableWith'])) {
            $models->load($models->first()->getAllSearchableWith());
        }

        if (method_exists($models->first(), 'getRichTextFieldsSearchable') && is_callable([$models->first(), 'getRichTextFieldsSearchable'])) {
            $fields = collect($models->first()->getRichTextFieldsSearchable())
                ->map(fn ($field) => 'richText'.Str::studly($field))
                ->all();

            $models->load($fields);
        }

        $index = $this->meilisearch->index($models->first()->searchableAs());

        if ($this->usesSoftDelete($models->first()) && $this->softDelete) {
            $models->each->pushSoftDeleteMetadata();
        }

        $objects = $models->map(function ($model) {
            if (empty($searchableData = $model->toSearchableArray())) {
                return;
            }

            return array_merge(
                $searchableData,
                $model->scoutMetadata(),
                [$model->getScoutKeyName() => $model->getScoutKey()],
            );
        })->filter()->values()->all();

        if (! empty($objects)) {
            $index->addDocuments($objects, $models->first()->getScoutKeyName());
        }
    }
}
