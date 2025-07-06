<?php

namespace App\Enums;

enum BrandType: string
{
    case Normal = "normal";
    case Expert = "expert";

    public static function types(): array
    {
        return [
            self::Normal->value,
            self::Expert->value,
        ];
    }
}
