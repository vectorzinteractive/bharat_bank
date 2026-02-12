<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GenerateModulePermissions extends Command
{
    protected $signature = 'modules:generate-permissions';
    protected $description = 'Generate CRUD permissions for all existing modules';

    public function handle()
    {
        $this->info('🔍 Scanning enabled modules...');

        foreach (Module::allEnabled() as $module) {

            $moduleName = strtolower($module->getName());

            $permissions = [
                "$moduleName.access",
                "$moduleName.create",
                "$moduleName.edit",
                "$moduleName.delete",
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }

            $this->info("✅ Permissions created for: {$module->getName()}");
        }

        $allPermissions = Permission::all();

        foreach (['super-admin', 'admin'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($allPermissions);
                $this->info("🔐 Assigned all permissions to {$roleName}");
            }
        }

        $this->info('🎉 Module permission generation completed!');
    }
}
