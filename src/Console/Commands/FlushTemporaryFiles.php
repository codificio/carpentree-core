<?php

namespace Carpentree\Core\Console\Commands;

use Carpentree\Core\Services\FlushTemporaryFilesService;

use Illuminate\Console\Command;

class FlushTemporaryFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carpentree:flush-temp-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old temporary media files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new FlushTemporaryFilesService();
        $service->flush();

        $this->info('Files correctly removed.');
    }
}
