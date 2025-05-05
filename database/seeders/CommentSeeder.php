<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommentSeeder extends Seeder
{
    public function run()
    {

        // پاک کردن تمامی کامنت‌ها قبل از وارد کردن کامنت‌های جدید
        DB::table('comments')->truncate();



        $now = Carbon::now();

        // کامنت‌های اصلی
        $mainComments = [];
        for ($i = 1; $i <= 6; $i++) {
            $mainComments[] = [
                'name' => "User$i",
                'last_name' => "Lastname$i",
                'email' => "user$i@example.com",
                'mobile' => "0912345678$i",
                'comment' => "This is main comment $i",
                'status' => 'pending',
                'parent_id' => null,
                'blog_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('comments')->insert($mainComments);

        // گرفتن IDهای کامنت‌های اصلی
        $parentIds = DB::table('comments')->orderBy('id', 'desc')->take(6)->pluck('id')->toArray();

        // پاسخ‌ها به برخی کامنت‌ها
        $replies = [];
        for ($j = 1; $j <= 4; $j++) {
            $replies[] = [
                'name' => 'Admin',
                'last_name' => 'Reply',
                'email' => 'admin@example.com',
                'mobile' => '09120000000',
                'comment' => "This is a reply to comment $j",
                'status' => 'approved',
                'parent_id' => $parentIds[$j - 1], // وصل کردن به کامنت اصلی
                'blog_id' => $parentIds[$j - 1], // وصل کردن به کامنت اصلی
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('comments')->insert($replies);
    }
}

