<?php

namespace Carpentree\Core\Console\Commands;

use Carpentree\Core\Services\FlushTemporaryMediaService;

use Illuminate\Console\Command;

class FlushTemporaryMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carpentree:flush-temp-media';

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
        $service = new FlushTemporaryMediaService();
        $service->flush();

        $this->info('Media correctly removed.');
    }
}
