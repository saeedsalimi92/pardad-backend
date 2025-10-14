<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;


/**
 * @property int $id
 */
class Faq extends Model
{
    use HasFactory, SoftDeletes, ActivityLog;

    protected $fillable = [
        'question',
        'answer',
        'priority',
        'is_active',
        'faqable_id',
        'faqable_type'
    ];

    public static array $indexRule = [
        "status" => "nullable|in:active,inactive",
    ];

    public static array $createRule = [
        "status" => "nullable|in:active,inactive",
        "question" => "required|string",
        "answer" => "required|string",
    ];

    public static array $updateRule = [
        "status" => "nullable|in:active,inactive",
        "question" => "required|string",
        "answer" => "required|string",
    ];

    public static array $destroyRule = [

    ];

    public function statusTranslate(): Attribute
    {
        return Attribute::make(
            get: fn () => __("attributes.faq.status.{$this->status}"),
        );
    }

    public function faqable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

       

}
