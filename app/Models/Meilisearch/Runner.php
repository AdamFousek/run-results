<?php

declare(strict_types=1);


namespace App\Models\Meilisearch;

use Illuminate\Support\Carbon;

class Runner
{
    private int $id;
    private ?int $userId = null;
    private string $firstName;
    private string $lastName;
    private int $year;
    private ?string $city = null;
    private ?string $club = null;
    private ?string $gender = null;
    private int $resultsCount;
    private ?Carbon $createdAt = null;
    private ?Carbon $updatedAt = null;
    private Carbon $upsertedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getClub(): ?string
    {
        return $this->club;
    }

    public function setClub(?string $club): void
    {
        $this->club = $club;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?Carbon $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?Carbon $updatedAt): void
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

    public function getResultsCount(): int
    {
        return $this->resultsCount;
    }

    public function setResultsCount(int $resultsCount): void
    {
        $this->resultsCount = $resultsCount;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
