<?php

namespace App\Enums;

enum LocalEnum: string
{
    case Persian = "fa";
    case English = "en";

    public static function allLocale(): array
    {
        return [
            self::Persian->value,
            self::English->value,
        ];
    }
}
