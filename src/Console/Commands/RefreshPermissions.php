<?php

namespace Carpentree\Core\Console\Commands;

use Carpentree\Core\Services\RefreshPermissionsService;
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
        $service = new RefreshPermissionsService();
        $service->refresh();

        $this->info('Permissions correctly refreshed.');
    }
}
