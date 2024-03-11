<?php

declare(strict_types=1);


namespace App\Models\Meilisearch\Result;

class Result
{
    private int $id;
    private ResultRunner $runner;
    private ResultRace $race;
    private int $startingNumber;
    private int $position;
    private ?string $time = null;
    private ?string $category = null;
    private ?string $categoryPosition = null;
    private ?string $club = null;
    private bool $dnf;
    private bool $dns;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRunner(): ResultRunner
    {
        return $this->runner;
    }

    public function setRunner(ResultRunner $runner): void
    {
        $this->runner = $runner;
    }

    public function getRace(): ResultRace
    {
        return $this->race;
    }

    public function setRace(ResultRace $race): void
    {
        $this->race = $race;
    }

    public function getStartingNumber(): int
    {
        return $this->startingNumber;
    }

    public function setStartingNumber(int $startingNumber): void
    {
        $this->startingNumber = $startingNumber;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(?string $time): void
    {
        $this->time = $time;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    public function getCategoryPosition(): ?string
    {
        return $this->categoryPosition;
    }

    public function setCategoryPosition(?string $categoryPosition): void
    {
        $this->categoryPosition = $categoryPosition;
    }

    public function getClub(): ?string
    {
        return $this->club;
    }

    public function setClub(?string $club): void
    {
        $this->club = $club;
    }

    public function isDnf(): bool
    {
        return $this->dnf;
    }

    public function setDnf(bool $dnf): void
    {
        $this->dnf = $dnf;
    }

    public function isDns(): bool
    {
        return $this->dns;
    }

    public function setDns(bool $dns): void
    {
        $this->dns = $dns;
    }
}
