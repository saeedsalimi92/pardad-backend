<?php

namespace App\Enums;

enum Locale: string
{
    case Persian = "fa";
    case English = "en";
    case Arabian = "ar";

    public static function allLocale(): array
    {
        return [
            self::Persian->value,
            self::English->value,
            self::Arabian->value,
        ];
    }
}
