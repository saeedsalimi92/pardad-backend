<?php

return [
    'blog' => [
        'status' => [
            \App\Models\Blog::STATUS_DRAFT => 'پیش نویس',
            \App\Models\Blog::STATUS_ARCHIVE => 'آرشیو',
            \App\Models\Blog::STATUS_PUBLISH => 'منتشر شده',
        ]
    ]
];
