<?php

return [
    'blog' => [
        'status' => [
            \App\Models\Blog::STATUS_DRAFT => 'پیش نویس',
            \App\Models\Blog::STATUS_ARCHIVE => 'آرشیو',
            \App\Models\Blog::STATUS_PUBLISH => 'منتشر شده',
        ]
    ],
    'category' => [
        'status' => [
            \App\Models\Category::STATUS_ACTIVE => 'پیش نویس',
            \App\Models\Category::STATUS_INACTIVE => 'آرشیو',
        ],
        'type' => [
            \App\Models\Category::TYPE_NORMAL => "عادی",
            \App\Models\Category::TYPE_EXPERT => "حرفه ای",
        ]
    ],
    'comment' => [
        'status' => [
            \App\Models\Comment::STATUS_PENDING => 'در انتظار',
            \App\Models\Comment::STATUS_REJECT => 'رد شده',
            \App\Models\Comment::STATUS_APPROVED => 'تایید شده',
        ]
    ],
];
