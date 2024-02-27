<?php

declare(strict_types=1);


namespace App\Http\Transformers\Result;

use App\Models\Illuminate\UploadFileResult;
use App\Models\Illuminate\UploadFileResultRow;
use Illuminate\Support\Collection;

class ResultUploadsTransformer
{
    /**
     * @param array<int, ?UploadFileResult> $items
     * @return array<array{
     *     id: int,
     *     total_rows: int,
     *     processed_rows: int,
     *     failed_rows: int,
     *     processed_at: string|null,
     *     created_at: string|null,
     *     rows: array<int, array{
     *     id: int,
     *     row_number: int,
     *     data: string,
     *     error: string
     *    }>
     * }>
     * @throws \JsonException
     */
    public function transform(Collection|array $items): array
    {
        $transformedItems = [];
        foreach ($items as $item) {
            if (!$item instanceof UploadFileResult) {
                continue;
            }

            $transformedItems[] = $this->transformItem($item);
        }

        return $transformedItems;
    }

    /**
     * @param UploadFileResult $item
     * @return array{
     *     id: int,
     *     total_rows: int,
     *     processed_rows: int,
     *     failed_rows: int,
     *     processed_at: string|null,
     *     created_at: string|null,
     *     rows: array<int, array{
     *     id: int,
     *     row_number: int,
     *     data: string,
     *     error: string
     *     }>
     * }
     * @throws \JsonException
     */
    private function transformItem(UploadFileResult $item): array
    {
        return [
            'id' => $item->id,
            'total_rows' => $item->total_rows,
            'processed_rows' => $item->processed_rows,
            'failed_rows' => $item->failed_rows,
            'processed_at' => $item->processed_at?->format('j.n.Y H:i:s'),
            'created_at' => $item->created_at?->format('j.n.Y H:i:s'),
            'rows' => $this->transformRows($item->rows),
        ];
    }

    /**
     * @param array<int, UploadFileResultRow>|\Illuminate\Database\Eloquent\Collection $rows
     * @return array<int, array{
     *     id: int,
     *     row_number: int,
     *     data: string,
     *     error: string
     * }>
     * @throws \JsonException
     */
    private function transformRows(array|\Illuminate\Database\Eloquent\Collection $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            if (!$row instanceof UploadFileResultRow) {
                continue;
            }

            $data = json_decode($row->data, false, 512, JSON_THROW_ON_ERROR);
            $result[] = [
                'id' => $row->id,
                'row_number' => $row->row_number,
                'data' => implode(',', $data),
                'error' => trans($row->error),
            ];
        }

        return $result;
    }
}
