<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'last_name', 'mobile', 'email', 'comment', 'user_id', 'parent_id' , 'blog_id', 'is_reply'  ,'status'];

    // رابطه با کامنت‌های پاسخ
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id'); // اصلاح شده
    }
}
