<?php

use App\Enums\BlogStatus;
use App\Enums\CategoryType;
use App\Enums\CommentStatus;
use App\Enums\Status;

return [
    'blog' => [
        'status' => [
            BlogStatus::Draft->value => 'پیش نویس',
            BlogStatus::Archived->value => 'آرشیو',
            BlogStatus::Published->value => 'منتشر شده',
        ]
    ],
    'category' => [
        'status' => [
            Status::Active->value => 'فعال',
            Status::InActive->value => 'غیرفعال',
        ],
        'type' => [
            CategoryType::Normal->value => "عادی",
            CategoryType::Expert->value => "حرفه ای",
        ]
    ],
    'comment' => [
        'status' => [
            CommentStatus::Pending->value => 'در انتظار',
            CommentStatus::Rejected->value => 'رد شده',
            CommentStatus::Approved->value => 'تایید شده',
        ]
    ],
    'faq' => [
        'status' => [
            Status::Active->value => 'فعال',
            Status::InActive->value => 'غیرفعال',
        ]
    ],
    'brand' => [
        'status' => [
            Status::Active->value => 'فعال',
            Status::InActive->value => 'غیرفعال',
        ]
    ],
];
