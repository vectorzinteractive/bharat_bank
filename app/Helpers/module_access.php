<?php

use Nwidart\Modules\Facades\Module;

if (!function_exists('canAccessModule')) {
    function canAccessModule(string $module): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $permission = strtolower($module) . '.access';

        return
            Module::has($module) &&
            Module::find($module)->isEnabled() &&
            auth()->user()->can($permission);
    }
}
