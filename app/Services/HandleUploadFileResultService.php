<?php

declare(strict_types=1);


namespace App\Services;

use App\Models\Result;
use App\Models\Runner;
use App\Models\UploadFileResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HandleUploadFileResultService
{
    private const POSITION = 0;
    private const STARTING_NUMBER = 1;
    private const LAST_NAME = 2;
    private const FIRST_NAME = 3;
    private const DATE_OF_BIRTH = 4;
    private const CLUB = 5;
    private const TIME = 6;
    private const CATEGORY_POSITION = 7;
    private const CATEGORY = 8;

    public function handle(UploadFileResult $results): void
    {
        $file = fopen(Storage::path($results->file_path), "rb");
        if ($file === false) {
            return;
        }

        while (($data = fgetcsv($file, 1000, ",")) !== false )
        {
            $runner = $this->resolveRunner($data);
            if ($runner === null) {
                $runner = $this->createNewRunner($data);
            }
            $result = new Result();
            $result->race_id = $results->race_id;
            $result->runner_id = $runner->id;
            $result->position = (int)$data[self::POSITION];
            $result->starting_number = (int)$data[self::STARTING_NUMBER];
            $result->time = $data[self::TIME];
            $result->category = $data[self::CATEGORY];
            $result->category_position = (int)$data[self::CATEGORY_POSITION];
            $result->save();
        }

        fclose($file);

        Storage::delete($results->file_path);
    }

    /**
     * @param string[] $data
     * @return ?Runner
     */
    private function resolveRunner(array $data): ?Runner
    {
        $birthDate = explode('.', $data[self::DATE_OF_BIRTH] ?? '0.0.0');
        $year = (int)($birthDate[2] ?? 0);
        $month = (int)($birthDate[1] ?? 0);
        $day = (int)($birthDate[0] ?? 0);

        $data[self::FIRST_NAME] = ucfirst(mb_convert_case($data[self::FIRST_NAME], MB_CASE_LOWER));
        $data[self::LAST_NAME] = ucfirst(mb_convert_case($data[self::LAST_NAME], MB_CASE_LOWER));
        $runners = Runner::query()
            ->where('first_name', $data[self::FIRST_NAME])
            ->where('last_name', $data[self::LAST_NAME])
            ->get();

        $runners = $runners->filter(function (Runner $runner) use ($year) {
            return $runner->year === $year;
        });

        if ($year === 0 && $month === 0 && $day === 0) {
            if ($runners->count() === 1) {
                return $runners->first();
            }

            return $this->createNewRunner($data);
        }

        $results = [];
        foreach ($runners as $runner) {
            if ($runner->year !== 0 && $runner->year !== $year) {
                continue;
            }

            $runner->setVisible(['day', 'month']);
            $monthCheck = null;
            $dayCheck = null;
            if ($runner->month !== null) {
                if ($month !== 0) {
                    $monthCheck = Hash::check((string)$month, $runner->month);
                }
            } else {
                // If we found only one runner, probably it will be him
                if ($runners->count() === 1) {
                    $monthCheck = true;
                    $runner->month = $month !== 0 && $runner->month === null ? Hash::make((string)$month) : $runner->month;
                    $runner->save();
                }
            }
            if ($runner->day !== null) {
                if ($day !== 0) {
                    $dayCheck = Hash::check((string)$day, $runner->day);
                }
            } else {
                // If we found only one runner, probably it will be him
                if ($runners->count() === 1) {
                    $dayCheck = true;
                    $runner->day = $day !== 0 && $runner->day === null ? Hash::make((string)$day) : $runner->day;
                    $runner->save();
                }
            }

            if ($monthCheck && $dayCheck && $runners->count() === 1) {
                return $runner;
            }

            $results[$runner->id] = [
                'month' => $monthCheck,
                'day' => $dayCheck,
            ];
        }

        if ($results === []) {
            return $this->createNewRunner($data);
        }

        $bestMatch = null;
        foreach ($results as $runnerId => $values) {
            if ($bestMatch === null) {
                $score = $values['month'] && $values['day'] ? 3 : 0;
                $score += $values['month'] ? 2 : 0;
                $score += $values['day'] ? 1 : 0;
                if (!isset($values['month'], $values['day'])) {
                    $score = 1;
                }
                $bestMatch = [
                    'runnerId' => $runnerId,
                    'score' => $score,
                ];
            } else {
                $score = $values['month'] && $values['day'] ? 3 : 0;
                $score += $values['month'] ? 2 : 0;
                $score += $values['day'] ? 1 : 0;
                if (!isset($values['month'], $values['day'])) {
                    $score = 1;
                }
                if ($score > $bestMatch['score']) {
                    $bestMatch = [
                        'runnerId' => $runnerId,
                        'score' => $score,
                    ];
                }
            }
        }

        if ($bestMatch['score'] === 0) {
            return $this->createNewRunner($data);
        }

        return Runner::whereId($bestMatch['runnerId'])->first();
    }


    /**
     * @param string[] $data
     * @return Runner
     */
    private function createNewRunner(array $data): Runner
    {
        $birthDate = explode('.', $data[self::DATE_OF_BIRTH] ?? '0.0.0');
        $year = (int)($birthDate[2] ?? 0);
        $month = (int)($birthDate[1] ?? 0);
        $day = (int)($birthDate[0] ?? 0);

        $runner = new Runner();
        $runner->first_name = ucfirst($data[self::FIRST_NAME]);
        $runner->last_name = ucfirst($data[self::LAST_NAME]);
        $runner->year = $year;
        $runner->month = $month === 0 ? null : Hash::make((string)$month);
        $runner->day = $day === 0 ? null : Hash::make((string)$day);
        $runner->club = $data[self::CLUB];
        $runner->save();

        return $runner;
    }
}
