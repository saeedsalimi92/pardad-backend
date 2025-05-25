<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSyncCommand extends Command
{
    protected $signature = 'permission:sync';

    protected $description = 'Command description';

    public function handle(): void
    {
        $roles = [
            'super admin',
            'seo manager',
            'marketing manager',
        ];

        $permissions = [
            // blog crud
            'admin.blog.index',
            'admin.blog.store',
            'admin.blog.show',
            'admin.blog.update',
            'admin.blog.destroy',

            // category crud
            'admin.category.index',
            'admin.category.store',
            'admin.category.show',
            'admin.category.update',
            'admin.category.destroy',

            // comment (change status, index)
            'admin.comment.index',
            'admin.comment.change_status',
            'admin.comment.reply',
        ];

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::query()->whereIn('name', $permissions)->delete();
        Role::query()->whereIn('name', $roles)->delete();

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $superAdmin = Role::create(['name' => 'super admin']);
        $seoManager = Role::create(['name' => 'seo manager']);
        $author = Role::create(['name' => 'author']);
        $marketingManager = Role::create(['name' => 'marketing manager']);

        $superAdmin->givePermissionTo(Permission::all());

        $seoPermissions = array_filter($permissions, function($perm) {
            return str_starts_with($perm, 'blog.') || str_starts_with($perm, 'category.');
        });

        $authorPermissions = array_filter($permissions, function($perm) {
            return str_starts_with($perm, 'blog.') || str_starts_with($perm, 'category.');
        });

        $seoManager->givePermissionTo($seoPermissions);
        $author->givePermissionTo($authorPermissions);

        $marketingPermissions = array_filter($permissions, function($perm) {
            return str_starts_with($perm, 'blog.');
        });

        $marketingManager->givePermissionTo($marketingPermissions);

        $this->info('Roles and permissions created and assigned successfully.');
    }
}
