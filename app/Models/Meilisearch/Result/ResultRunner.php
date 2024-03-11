<?php

declare(strict_types=1);


namespace App\Models\Meilisearch\Result;


use App\Models\Illuminate\Enums\RunnerGenderEnum;

class ResultRunner
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private int $year;
    private ?RunnerGenderEnum $gender = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function getGender(): ?RunnerGenderEnum
    {
        return $this->gender;
    }

    public function setGender(?RunnerGenderEnum $gender): void
    {
        $this->gender = $gender;
    }
}
