<?php

declare(strict_types=1);


namespace App\Models\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
