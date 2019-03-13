<?php

namespace Carpentree\Core\Console\Commands;

use Carpentree\Core\Services\RefreshPermissionsService;
use Carpentree\Core\Services\RefreshRolesService;
use Illuminate\Console\Command;

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
    protected $description = 'Refresh permissions and roles in database';

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
        $servicePermission = new RefreshPermissionsService();
        $serviceRole = new RefreshRolesService();

        $servicePermission->refresh();
        $serviceRole->refresh();

        $this->info('Permissions correctly refreshed.');
    }
}
