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

    protected $description = 'Sync roles and permissions from files';

    public function handle(): void
    {
        // ایجاد یا دریافت کاربر اصلی
        $user = User::query()->firstOrCreate(
            ["email" => "info@pardad.com"],
            [
                "name" => "admin",
                "email" => "info@pardad.com",
                "password" => Hash::make("pardad"),
                "mobile" => "09121111111",
                "status" => Status::Active->value
            ]
        );

        // گرفتن نقش‌ها و پرمیژن‌ها از config
        $roles = config('role_permissions');

        // پاک کردن کش دسترسی‌ها
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($roles as $roleName => $roleData) {
            // ایجاد یا دریافت نقش
            $role = Role::query()->firstOrCreate(
                ['name' => $roleName],
                [
                    'name' => $roleName,
                    'display_name' => $roleData['display_name'],
                ]
            );

            $permissions = $roleData['permissions'] ?? [];

            // ایجاد دسترسی‌ها اگر وجود ندارند
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }

            // سینک دسترسی‌ها با نقش
            $role->syncPermissions($permissions);

            // اگر super-admin بود همه دسترسی‌ها
            if ($roleName === 'super-admin') {
                $role->givePermissionTo(Permission::all());
                $user->syncRoles([$roleName]);
            }
        }

        $this->info('Roles and permissions created and assigned successfully.');
    }
}
