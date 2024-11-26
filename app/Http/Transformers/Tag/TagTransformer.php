<?php

declare(strict_types=1);


namespace App\Http\Transformers\Tag;

use Spatie\Tags\Tag;

class TagTransformer
{
    /**
     * @param Tag[] $tags
     * @return array<array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     }>
     */
    public function transformMulti(array $tags): array
    {
        $transformedTags = [];
        foreach ($tags as $tag) {
            if (!$tag instanceof Tag) {
                continue;
            }

            $transformedTags[] = $this->transform($tag);
        }

        return $transformedTags;
    }

    /**
     * @param Tag $tag
     * @return array{
     *     id: int,
     *     name: string,
     *     slug: string,
     * }
     */
    public function transform(Tag $tag): array
    {
        return [
            'id' => $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug,
        ];
    }
}
