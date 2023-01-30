<?php

declare(strict_types=1);


namespace App\Models\Enum;

enum UserRolesEnum: string
{
    case ADMIN = 'admin';
    case  USER  = 'user';
    case  GUEST = 'guest';

    public static function getRoles(): array
    {
        return array_column(self::cases(), 'value');
    }

}
