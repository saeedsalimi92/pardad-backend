<?php

namespace App\Enums;

enum Status: string
{
    case Active = "active";
    case InActive = "inactive";

    public static function statuses(): array
    {
        return [
            self::Active->value,
            self::InActive->value,
        ];
    }
}
