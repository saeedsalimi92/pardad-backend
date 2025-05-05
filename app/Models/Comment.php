<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'last_name', 'mobile', 'email', 'comment', 'user_id', 'parent_id', 'is_reply'  ,'status'];

    // رابطه با کامنت‌های پاسخ
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->whereNotNull('parent_id');
    }

// فقط کامنت‌های اصلی
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
    // رابطه با کاربر (فقط برای کامنت‌های پاسخ)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // رابطه با کامنت والد (اگر کامنت پاسخ باشد)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
