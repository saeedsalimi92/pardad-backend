<?php

namespace App\Enums;

enum CommentStatus: string
{
    case Approved = "approved";
    case Rejected = "rejected";
    case Pending = "pending";

    public static function statuses(): array
    {
        return [
            self::Pending->value,
            self::Rejected->value,
            self::Approved->value,
        ];
    }
}
