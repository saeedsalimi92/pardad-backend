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

        // تعریف نقش‌ها و نام فارسی
        $roles = [
            'super-admin' => 'ادمین اصلی',
            'admin' => 'ادمین',
            'seo-manager' => 'مدیر سئو',
            'marketing-manager' => 'مدیر مارکتینگ',
            'author' => 'محتوا نویس',
        ];

        // پاک کردن کش دسترسی‌ها
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($roles as $roleName => $roleFaName) {

            // ایجاد یا دریافت نقش
            $role = Role::query()->firstOrCreate(
                ['name' => $roleName],
                [
                    'name' => $roleName,
                    'display_name' => $roleFaName,
                ]
            );

            // بارگذاری دسترسی‌ها از فایل
            $permissions = file_exists(app_path("Permissions/{$roleName}.php"))
                ? include app_path("Permissions/{$roleName}.php")
                : [];

            // ایجاد دسترسی‌ها اگر وجود ندارند
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }

            // سینک دسترسی‌ها با نقش
            $role->syncPermissions($permissions);

            // اگر super-admin است، همه دسترسی‌ها
            if ($roleName === 'super-admin') {
                $role->givePermissionTo(Permission::all());
                $user->syncRoles([$roleName]);
            }
        }

        $this->info('Roles and permissions created and assigned successfully.');
    }
}
