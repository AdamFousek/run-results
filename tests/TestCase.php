<?php

namespace Tests;

use App\Models\Illuminate\Enums\RoleEnum;
use App\Models\Illuminate\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        User::firstOrCreate([
            'username' => 'adamfousek',
            'password' => 'password',
            'email' => 'test@test.com',
            'role' => RoleEnum::ADMIN->value,
        ]);
    }
}
