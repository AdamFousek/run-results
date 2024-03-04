<?php

namespace Tests\Feature;

use App\Models\Illuminate\Enums\RoleEnum;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Illuminate\Race;
use App\Models\Illuminate\Runner;
use App\Models\Illuminate\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use JetBrains\PhpStorm\NoReturn;
use Tests\TestCase;

class RunnerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::whereRole(RoleEnum::ADMIN)->first();

        Runner::factory()->create();
    }

    /**
     * A basic feature test example.
     */
    public function test_create_runner(): void
    {
        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.runners.store'), [
                'first_name' => 'Adam',
                'last_name' => 'Fousek',
                'day' => 1,
                'month' => 1,
                'year' => 1990,
                'city' => 'Brno',
                'club' => 'RunCzech',
                'gender' => RunnerGenderEnum::MALE->value,
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('runners', [
            'first_name' => 'Adam',
            'last_name' => 'Fousek',
            'year' => 1990,
            'city' => 'Brno',
            'club' => 'RunCzech',
        ]);

        $runner = Runner::whereFirstName('Adam')->whereLastName('Fousek')->first();
        $runner->setVisible(['day', 'month']);

        $this->assertTrue(Hash::check('1', $runner->day ?? ''));
        $this->assertTrue(Hash::check('1', $runner->month ?? ''));
    }

    #[NoReturn]
    public function test_update_runner(): void
    {
        $runner = Runner::all()->first();

        if ($runner === null) {
            $this->fail('Runner not found');
        }

        $response = $this
            ->actingAs($this->admin)
            ->post(route('admin.runners.update', $runner->id), [
                'first_name' => 'Pepa',
                'last_name' => 'Kousek',
                'day' => 2,
                'month' => 2,
                'year' => 1995,
                'city' => 'Ostrava',
                'club' => 'RunOva',
            ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('runners', [
            'first_name' => 'Pepa',
            'last_name' => 'Kousek',
            'year' => 1995,
            'city' => 'Ostrava',
            'club' => 'RunOva',
        ]);

        $runner->refresh();
        $runner->setVisible(['day', 'month']);

        $this->assertTrue(Hash::check('2', $runner->day ?? ''));
        $this->assertTrue(Hash::check('2', $runner->month ?? ''));
    }

    /**
     * @TODO failing runner not deleted from DB
     *
     * @return void
     */
    #[NoReturn] public function test_remove_runner(): void
    {
        $runner = Runner::all()->first();
        $race = Race::factory()->create();

        if ($runner === null) {
            $this->fail('Runner not found');
        }

        $runner->results()->create([
            'race_id' => $race->id,
            'time' => 1000,
            'starting_number' => 1,
            'position' => 1,
            'category' => '',
            'category_position' => 0,
        ]);

        $response = $this
            ->actingAs($this->admin)
            ->delete(route('admin.runners.destroy', $runner->id));

        $response->assertStatus(302);

        dd(Runner::whereId($runner->id)->first());
    }
}
