<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;

class BlogPolicy
{
    public function __construct()
    {
    }

    public function SuperIndex(User $user): bool
    {
        if ($user->can('admin.blog.super_index')) {
            return true;
        }

        return false;
    }

    public function index(User $user): bool
    {
        if ($user->can('admin.blog.index')) {
            return true;
        }

        return false;
    }

    public function SuperShow(User $user): bool
    {
        if ($user->can('admin.blog.super_show')) {
            return true;
        }

        return false;
    }

    public function show(User $user, Blog $blog): bool
    {
        if ($user->can('admin.blog.show') && $blog->author_id == $user->id) {
            return true;
        }

        return false;
    }

    public function store(User $user): bool
    {
        if ($user->can('admin.blog.store')) {
            return true;
        }

        return false;
    }

    public function update(User $user, Blog $blog): bool
    {
        if ($user->can('admin.blog.update') && $blog->author_id == $user->id) {
            return true;
        }

        return false;
    }

    public function SuperUpdate(User $user): bool
    {
        if ($user->can('admin.blog.super_update')) {
            return true;
        }

        return false;
    }

    public function SuperDestroy(User $user): bool
    {
        if ($user->can('admin.blog.super_destroy')) {
            return true;
        }

        return false;
    }

    public function destroy(User $user, Blog $blog): bool
    {
        if ($user->can('admin.blog.destroy') && $blog->author_id == $user->id) {
            return true;
        }

        return false;
    }

    public function change_status(User $user): bool
    {
        if ($user->can('admin.blog.change_status')) {
            return true;
        }

        return false;
    }

    public function list(User $user): bool
    {
        if ($user->can('admin.blog.list')) {
            return true;
        }

        return false;
    }


    public function faqIndex(User $user): bool
    {
        if ($user->can('admin.blog.faq_index')) {
            return true;
        }

        return false;
    }

    public function faqShow(User $user): bool
    {
        if ($user->can('admin.blog.faq_show')) {
            return true;
        }

        return false;
    }

    public function faqStore(User $user): bool
    {
        if ($user->can('admin.blog.faq_store')) {
            return true;
        }

        return false;
    }

    public function faqUpdate(User $user): bool
    {
        if ($user->can('admin.blog.faq_update')) {
            return true;
        }

        return false;
    }

    public function faqDestroy(User $user): bool
    {
        if ($user->can('admin.blog.faq_destroy')) {
            return true;
        }

        return false;
    }

    public function bentoLayoutUpdate(User $user): bool
    {
        if ($user->can('admin.blog.bento_layout_update')) {
            return true;
        }

        return false;
    }
}
