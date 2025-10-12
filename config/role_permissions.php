<?php

return [
    'super-admin' => [
        'display_name' => 'ادمین اصلی',
        'permissions' => [

            'admin.role.index',
            'admin.role.store',
            'admin.role.update',
            'admin.role.show',
            'admin.role.assign_to_user',
            'admin.role.give_permission',

            // bento layout
            'admin.bento_layout.index',
            'admin.bento_layout.store',
            'admin.bento_layout.show',
            'admin.bento_layout.update',
            'admin.bento_layout.destroy',

            // permission
            'admin.permission.index',

            // blog faq
            'admin.blog.faq_index',
            'admin.blog.faq_store',
            'admin.blog.faq_show',
            'admin.blog.faq_update',
            'admin.blog.faq_destroy',

            // blog crud
            'admin.blog.super_index',
            'admin.blog.super_statuses',
            'admin.blog.statuses',
            'admin.blog.index',
            'admin.blog.list',
            'admin.blog.store',
            'admin.blog.super_show',
            'admin.blog.show',
            'admin.blog.update',
            'admin.blog.super_update',
            'admin.blog.super_destroy',
            'admin.blog.destroy',
            'admin.blog.bento_layout_update',



            // blog crud
            'admin.product.super_index',
            'admin.product.super_statuses',
            'admin.product.statuses',
            'admin.product.index',
            'admin.product.list',
            'admin.product.store',
            'admin.product.super_show',
            'admin.product.show',
            'admin.product.update',
            'admin.product.super_update',
            'admin.product.super_destroy',
            'admin.product.destroy',
            'admin.product.bento_layout_update',

            // category crud
            'admin.category.index',
            'admin.category.list',
            'admin.category.store',
            'admin.category.show',
            'admin.category.update',
            'admin.category.destroy',

            // comment
            'admin.comment.index',
            'admin.comment.show',
            'admin.comment.change_status',
            'admin.comment.reply',


            // brand
            'admin.brand.index',
            'admin.brand.store',
            'admin.brand.show',
            'admin.brand.update',
            'admin.brand.destroy',
            'admin.brand.change_status',
            'admin.brand.change_priority',

            // redirect
            'admin.redirect.index',
            'admin.redirect.store',
            'admin.redirect.show',
            'admin.redirect.update',
            'admin.redirect.destroy',
            'admin.redirect.change_status',

            //user
            'admin.user.index',
            'admin.user.store',
            'admin.user.show',
            'admin.user.update',
            'admin.user.destroy',
            'admin.user.change_status',
            'admin.user.change_password',
        ],
    ],

    'admin' => [
        'display_name' => 'ادمین',
        'permissions' => [],
    ],

    'seo-manager' => [
        'display_name' => 'مدیر سئو',
        'permissions' => [

            // bento layout
            'admin.bento_layout.index',
            'admin.bento_layout.store',
            'admin.bento_layout.show',
            'admin.bento_layout.update',
            'admin.bento_layout.destroy',


            // blog faq
            'admin.blog.faq_index',
            'admin.blog.faq_store',
            'admin.blog.faq_show',
            'admin.blog.faq_update',
            'admin.blog.faq_destroy',

            // blog crud
            'admin.blog.super_index',
            'admin.blog.super_statuses',
            'admin.blog.statuses',
            'admin.blog.index',
            'admin.blog.list',
            'admin.blog.store',
            'admin.blog.super_show',
            'admin.blog.show',
            'admin.blog.update',
            'admin.blog.super_update',
            'admin.blog.super_destroy',
            'admin.blog.destroy',
            'admin.blog.bento_layout_update',

            // category crud
            'admin.category.index',
            'admin.category.list',
            'admin.category.store',
            'admin.category.show',
            'admin.category.update',
            'admin.category.destroy',

            // comment
            'admin.comment.index',
            'admin.comment.show',
            'admin.comment.change_status',
            'admin.comment.reply',


            // brand
            'admin.brand.index',
            'admin.brand.store',
            'admin.brand.show',
            'admin.brand.update',
            'admin.brand.destroy',
            'admin.brand.change_status',
            'admin.brand.change_priority',

            // redirect
            'admin.redirect.index',
            'admin.redirect.store',
            'admin.redirect.show',
            'admin.redirect.update',
            'admin.redirect.destroy',
            'admin.redirect.change_status',

            'admin.category.list',
            'admin.user.change_password',
        ],
    ],

    'marketing-manager' => [
        'display_name' => 'مدیر مارکتینگ',
        'permissions' => [
            // bento layout
            'admin.bento_layout.index',
            'admin.bento_layout.store',
            'admin.bento_layout.show',
            'admin.bento_layout.update',
            'admin.bento_layout.destroy',


            // blog faq
            'admin.blog.faq_index',
            'admin.blog.faq_store',
            'admin.blog.faq_show',
            'admin.blog.faq_update',
            'admin.blog.faq_destroy',

            // blog crud
            'admin.blog.super_index',
            'admin.blog.super_statuses',
            'admin.blog.statuses',
            'admin.blog.index',
            'admin.blog.list',
            'admin.blog.store',
            'admin.blog.super_show',
            'admin.blog.show',
            'admin.blog.update',
            'admin.blog.super_update',
            'admin.blog.super_destroy',
            'admin.blog.destroy',
            'admin.blog.bento_layout_update',

            // category crud
            'admin.category.index',
            'admin.category.list',
            'admin.category.store',
            'admin.category.show',
            'admin.category.update',
            'admin.category.destroy',

            // comment
            'admin.comment.index',
            'admin.comment.show',
            'admin.comment.change_status',
            'admin.comment.reply',


            // brand
            'admin.brand.index',
            'admin.brand.store',
            'admin.brand.show',
            'admin.brand.update',
            'admin.brand.destroy',
            'admin.brand.change_status',
            'admin.brand.change_priority',

            // redirect
            'admin.redirect.index',
            'admin.redirect.store',
            'admin.redirect.show',
            'admin.redirect.update',
            'admin.redirect.destroy',
            'admin.redirect.change_status',
            'admin.category.list',

            'admin.user.change_password',
        ],
    ],

    'author' => [
        'display_name' => 'محتوا نویس',
        'permissions' => [
            // bento layout
            'admin.bento_layout.index',
            'admin.bento_layout.store',
            'admin.bento_layout.show',
            'admin.bento_layout.update',
            'admin.bento_layout.destroy',


            // blog faq
            'admin.blog.faq_index',
            'admin.blog.faq_store',
            'admin.blog.faq_show',
            'admin.blog.faq_update',
            'admin.blog.faq_destroy',



            // blog crud
            'admin.blog.statuses',
            'admin.blog.index',
            'admin.blog.list',
            'admin.blog.store',
            'admin.blog.show',
            'admin.blog.update',
            'admin.blog.destroy',
            'admin.blog.bento_layout_update',


            //category

            'admin.category.list',

            'admin.user.change_password',
        ],
    ],
];
