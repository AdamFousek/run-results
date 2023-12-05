<?php

declare(strict_types=1);


namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginateService
{
    /**
     * @param LengthAwarePaginator $paginator
     * @return array<array<string, string|bool>>
     */
    public function resolveLinks(LengthAwarePaginator $paginator): array
    {
        $result = [];

        if ($paginator->lastPage() === 1) {
            return $result;
        }

        foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $link) {
            $result[] = [
                'link' => $link,
                'label' => $page,
                'active' => $page === $paginator->currentPage(),
            ];
        }

        return $result;
    }
}
