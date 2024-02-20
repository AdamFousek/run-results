<?php

declare(strict_types=1);


namespace App\Models\Illuminate\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
