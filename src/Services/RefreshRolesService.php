<?php

namespace Carpentree\Core\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RefreshRolesService
{
    public function refresh()
    {
        $config = config('carpentree.roles');

        // Forget Spatie Laravel Permissions cache
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function () use ($config) {

            $addedIds = array();

            // Add new permissions
            foreach ($config as $name => $permissions) {

                $role = Role::findOrCreate($name);
                $role->permissions()->delete();
                $addedIds[] = $role->id;

                if (sizeof($permissions) > 0) {
                    foreach ($permissions as $key) {
                        $permission = Permission::findByName($key);
                        if ($permission) {
                            $role->givePermissionTo($key);
                        }
                    }
                }
            }

            // Remove old permissions
            Role::whereNotIn('id', $addedIds)->delete();
        });
    }
}
