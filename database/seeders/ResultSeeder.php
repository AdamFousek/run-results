<?php

namespace Database\Seeders;

use App\Models\Race;
use App\Models\Result;
use App\Models\Runner;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $races = Race::all()->random(20);

        foreach ($races as $race) {
            $runners = Runner::all()->random(random_int(20, 50));

            $startingNumbers = range(1, count($runners));
            $positionNumbers = range(1, count($runners));

            $times = $this->generateTimes(count($runners));

            foreach ($runners as $runner) {
                $startingNumberKey = array_rand($startingNumbers);
                $positionNumberKey = array_rand($positionNumbers);
                $position = $positionNumbers[$positionNumberKey];
                Result::factory()->create([
                    'race_id' => $race->id,
                    'runner_id' => $runner->id,
                    'starting_number' => $startingNumbers[$startingNumberKey],
                    'position' => $position,
                    'time' => $times[$position],
                ]);
                unset($startingNumbers[$startingNumberKey]);
                unset($positionNumbers[$positionNumberKey]);
            }
        }
    }

    /**
     * @param int $count
     * @return int[]
     * @throws \Random\RandomException
     */
    private function generateTimes(int $count): array
    {
        $times = [];
        $lastTime = random_int(1500000, 5000000);
        for ($i = 1; $i <= $count; $i++) {
            $lastTime = $times[$i] = $lastTime + random_int(500, 60000);
        }

        return $times;
    }
}
