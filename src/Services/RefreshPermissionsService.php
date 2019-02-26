<?php

namespace Carpentree\Core\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RefreshPermissionsService
{
    public function refresh()
    {
        $config = config('carpentree.permissions');

        // Forget Spatie Laravel Permissions cache
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function () use ($config) {

            $addedIds = array();

            // Add new permissions
            foreach ($config as $groupKey => $group) {
                foreach ($group as $permissionKey) {
                    $fullKey = $groupKey.'.'.$permissionKey;
                    $permission = Permission::findOrCreate($fullKey);
                    $addedIds[] = $permission->id;
                }
            }

            // Remove old permissions
            Permission::whereNotIn('id', $addedIds)->delete();
        });
    }
}
