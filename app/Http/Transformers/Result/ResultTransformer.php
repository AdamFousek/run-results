<?php

declare(strict_types=1);


namespace App\Http\Transformers\Result;

use App\Models\Result;
use Illuminate\Support\Collection;

class ResultTransformer
{
    /**
     * @param array<int, ?Result> $items
     * @return array<array<string, string|int|float>>
     */
    public function transform(Collection|array $items): array
    {
        $transformedItems = [];
        foreach ($items as $item) {
            if (!$item instanceof Result) {
                continue;
            }

            $transformedItems[] = $this->transformItem($item);
        }

        return $transformedItems;
    }

    /**
     * @param Result $result
     * @return array<string, string|int|float>
     */
    private function transformItem(Result $result): array
    {
        return [
            'id' => $result->id,
            'runnerId' => $result->runner_id,
            'name' => $result->runner->first_name,
            'lastName' => $result->runner->last_name,
            'startingNumber' => $result->starting_number,
            'time' => explode('.', $result->time ?? '')[0],
            'position' => $result->position,
            'categoryPosition' => $result->category_position,
            'category' => $result->category,
            'DNF' => $result->DNF,
            'DNS' => $result->DNS,
        ];
    }
}
