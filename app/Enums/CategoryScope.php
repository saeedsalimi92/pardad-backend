<?php

namespace App\Enums;

enum CategoryScope: string
{
    case All = "all";
    case Main = "main";
    case Children = "children";

    public static function scopes()
    {
        return [
            self::All->value,
            self::Main->value,
            self::Children->value,
        ];
    }
}
