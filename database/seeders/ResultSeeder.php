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

            foreach ($runners as $runner) {
                Result::factory()->create([
                    'race_id' => $race->id,
                    'runner_id' => $runner->id,
                ]);
            }
        }
    }
}
