<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Brand extends Model
{
    //
    use HasFactory, SoftDeletes, HasImage, LogsActivity;

    protected $fillable = [
        'name',
        'status',
        'action',
        'file',
        'priority',
    ];
    public function statusTranslate(): Attribute
    {
        return Attribute::make(
            get: fn() => __("attributes.brand.status.{$this->status}"),
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
