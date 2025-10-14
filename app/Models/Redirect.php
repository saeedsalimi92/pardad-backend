<?php

namespace App\Models;

use App\Traits\ActivityLog;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 */
class Redirect extends Model
{
    use HasFactory, SoftDeletes, ActivityLog;

    protected $fillable = [
        'source_url', 'destination_url', 'status_code', 'status'
    ];
    public function statusTranslate(): Attribute
    {
        return Attribute::make(
            get: fn () => __("attributes.redirect.status.{$this->status}"),
        );
    }

       

}
