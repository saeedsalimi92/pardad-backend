<?php

namespace App\Console\Commands;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSyncCommand extends Command
{
    protected $signature = 'permission:sync';

    protected $description = 'Command description';

    public function handle(): void
    {

        $user = User::query()->firstOrCreate(
            ["email" => "info@pardad.com"],
            [
                "name" => "super admin",
                "email" => "info@pardad.com",
                "password" => Hash::make("pardad"),
                "status" => Status::Active->value
            ]
        );

        $roles = [
            'super admin',
            'seo manager',
            'marketing manager',
            'author',
        ];

        $permissions = [

            // role
            'admin.role.index',
            'admin.role.store',
            'admin.role.assign_to_user',
            'admin.role.give_permission',

            // permission
            'admin.permission.index',

            // blog faq
            'admin.blog.faq_index',
            'admin.blog.faq_store',
            'admin.blog.faq_update',
            'admin.blog.faq_destroy',

            // blog crud
            'admin.blog.index',
            'admin.blog.list',
            'admin.blog.store',
            'admin.blog.show',
            'admin.blog.update',
            'admin.blog.destroy',

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

            //user
            'admin.user.index',
        ];

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(
                ['name' => $permission],
                ['name' => $permission]
            );
        }

        foreach ($roles as $roleName) {
            $role = Role::query()->firstOrCreate(
                ['name' => $roleName],
                ['name' => $roleName]
            );

            if($roleName == 'super admin') {
                $role->givePermissionTo(Permission::all());
                $user->syncRoles([$roleName]);
            }
        }

        $this->info('Roles and permissions created and assigned successfully.');
    }
}
