<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $status_translate
 */
class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'is_admin',
        'first_name',
        'last_name',
        'mobile',
        'email',
        'content',
        'status'
    ];

    public function statusTranslate(): Attribute
    {
        return Attribute::make(
            get: fn () => __("attributes.comment.status.{$this->status}"),
        );
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name. " ". $this->last_name,
        );
    }

    public function commentable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blog(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
