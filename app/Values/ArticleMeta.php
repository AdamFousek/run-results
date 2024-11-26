<?php

declare(strict_types=1);


namespace App\Values;

use App\Casts\ArticleMetaCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;

readonly class ArticleMeta implements Castable, Arrayable
{
    /**
     * @param string $description
     * @param string[] $keywords
     */
    public function __construct(
        public string $description = '',
    ) {
    }

    /**
     * @param mixed[] $arguments
     * @return string
     */
    public static function castUsing(array $arguments): string
    {
        return ArticleMetaCast::class;
    }

    /**
     * @return array{
     *     description: string,
     *     keywords: string[],
     * }
     */
    public function toArray(): array
    {
        return [
            'description' => $this->description,
        ];
    }
}
