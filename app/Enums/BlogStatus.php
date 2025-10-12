<?php

namespace App\Enums;

enum BlogStatus: string
{
    case Draft = "draft";
    case Archived = "archived";
    case Published = "published";

    public static function statuses(): array
    {
        return [
            self::Draft->value,
            self::Archived->value,
            self::Published->value,
        ];
    }
}
