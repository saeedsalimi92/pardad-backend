<?php

namespace App\Models;

use App\Traits\ActivityLog;

class Permission extends \Spatie\Permission\Models\Permission
{
    use ActivityLog;

     
}
