<?php

namespace App\Enums\Types;

enum DeviceType: int
{
    case Phone = 1;
    case Tablet = 2;
    case PC_Laptop = 3;
    case Unknown = 4;

    public function get(): string
    {
        return match ($this) {
            self::Phone => 'phone',
            self::Tablet => 'tablet',
            self::PC_Laptop => 'pc/laptop',
            self::Unknown => 'unknow',
        };
    }

    public function getString($value): string
    {
        return match ($value) {
            self::Phone->value => 'phone',
            self::Tablet->value => 'tablet',
            self::PC_Laptop->value => 'pc/laptop',
            self::Unknown->value => 'unknow',
            default => 'unknow',
        };
    }
}
