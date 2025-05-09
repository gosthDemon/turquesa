<?php

namespace App\Enums\Status;

enum UserStatus: int
{
    case Inactive = 1;
    case Active = 2;
    case Banned = 3;
    case Locked = 4;

    public function get(): string
    {
        return match ($this) {
            self::Inactive => 'inactive',
            self::Active => 'active',
            self::Banned => 'banned',
            self::Locked => 'locked',
        };
    }
}
