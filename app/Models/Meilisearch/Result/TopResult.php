<?php

declare(strict_types=1);


namespace App\Models\Meilisearch\Result;

class TopResult
{
    private int $id;
    private int $topPosition;
    private ResultRunner $runner;
    private ResultRace $race;
    private string $time;

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

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): void
    {
        $this->time = $time;
    }

    public function getTopPosition(): int
    {
        return $this->topPosition;
    }

    public function setTopPosition(int $topPosition): void
    {
        $this->topPosition = $topPosition;
    }
}
