<?php

namespace Database\Seeders;

use App\Models\Runner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RunnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Runner::factory()
            ->count(100)
            ->create();
    }
}
