<?php

declare(strict_types=1);


namespace App\Models\Meilisearch;

class Race
{
    private int $id;
    private ?string $parentName = null;
    private ?string $parentSlug = null;
    private string $name;
    private string $slug;
    private string $description;
    private string $date;
    private string $time;
    private string $location;
    private string $region;
    private int $distance;
    private string $surface;
    private string $type;
    private string $tag;
    private int $vintage;
    private bool $isParent;
    private float $latitude;
    private float $longitude;
    private int $resultsCount;
    private string $createdAt;
    private string $updatedAt;
    private string $upsertedAt;

}
