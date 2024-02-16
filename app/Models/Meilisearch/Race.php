<?php

declare(strict_types=1);


namespace App\Models\Meilisearch;

use Illuminate\Support\Carbon;

class Race
{
    private int $id;
    private ?ParentRace $parent = null;
    private string $name;
    private string $slug;
    private string $description;
    private ?Carbon $date = null;
    private ?string $time = null;
    private string $location;
    private string $region;
    private int $distance;
    private string $surface;
    private string $type;
    private ?string $tag = null;
    private ?int $vintage = null;
    private bool $isParent;
    private ?float $latitude = null;
    private ?float $longitude = null;
    private int $resultsCount;
    private array $files;
    private Carbon $createdAt;
    private Carbon $updatedAt;
    private Carbon $upsertedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getParent(): ?ParentRace
    {
        return $this->parent;
    }

    public function setParent(?ParentRace $parent): void
    {
        $this->parent = $parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDate(): ?Carbon
    {
        return $this->date;
    }

    public function setDate(?Carbon $date): void
    {
        $this->date = $date;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(?string $time): void
    {
        $this->time = $time;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): void
    {
        $this->distance = $distance;
    }

    public function getSurface(): string
    {
        return $this->surface;
    }

    public function setSurface(string $surface): void
    {
        $this->surface = $surface;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): void
    {
        $this->tag = $tag;
    }

    public function getVintage(): ?int
    {
        return $this->vintage;
    }

    public function setVintage(?int $vintage): void
    {
        $this->vintage = $vintage;
    }

    public function isParent(): bool
    {
        return $this->isParent;
    }

    public function setIsParent(bool $isParent): void
    {
        $this->isParent = $isParent;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getResultsCount(): int
    {
        return $this->resultsCount;
    }

    public function setResultsCount(int $resultsCount): void
    {
        $this->resultsCount = $resultsCount;
    }

    /**
     * @return Files[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param Files[] $files
     * @return void
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(Carbon $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpsertedAt(): Carbon
    {
        return $this->upsertedAt;
    }

    public function setUpsertedAt(Carbon $upsertedAt): void
    {
        $this->upsertedAt = $upsertedAt;
    }

}
