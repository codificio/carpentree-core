<?php

namespace Carpentree\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RefreshPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carpentree:refresh-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh permissions in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
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
            // Permission::whereNotIn('id', $addedIds)->delete();

        });

        $this->info('Permissions correctly refreshed.');

    }
}
