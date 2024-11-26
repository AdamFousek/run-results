<?php

declare(strict_types=1);


namespace App\Casts;

use App\Values\ArticleMeta;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ArticleMetaCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes = []): ArticleMeta
    {
        if ($value === null) {
            return new ArticleMeta();
        }

        $meta = json_decode($value, true);

        return new ArticleMeta(
            description: $meta['description'],
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes = []): string
    {
        if (!$value instanceof ArticleMeta) {
            throw new InvalidArgumentException('Value must be of type ArticleMeta');
        }

        return json_encode($value->toArray());
    }
}
