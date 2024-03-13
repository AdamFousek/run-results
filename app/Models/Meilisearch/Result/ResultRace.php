<?php

declare(strict_types=1);


namespace App\Models\Meilisearch\Result;

use Illuminate\Support\Carbon;

class ResultRace
{
    private int $id;
    private string $name;
    private string $slug;
    private ?string $tag;
    private ?Carbon $date = null;
    private ?string $time = null;
    private ?int $vintage = null;
    private ?int $distance = null;
    private ?string $location = null;
    private ?string $surface = null;
    private ?string $type = null;
    private ?string $region = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): void
    {
        $this->distance = $distance;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getSurface(): ?string
    {
        return $this->surface;
    }

    public function setSurface(?string $surface): void
    {
        $this->surface = $surface;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }
}
