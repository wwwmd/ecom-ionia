<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log(string $module, string $action, ?string $description = null, array $props = []): void
    {
        ActivityLog::record($module, $action, $description, $props);
    }
}
