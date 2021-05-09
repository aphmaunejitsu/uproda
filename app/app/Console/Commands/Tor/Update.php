<?php

namespace App\Console\Commands\Tor;

use App\Services\DenyIpService;
use Illuminate\Console\Command;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tor:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get and update tor exit address';

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
     *
     * @return int
     */
    public function handle(DenyIpService $service)
    {
        $this->info('Start update tor exit address');
        $result = $service->updateTor();

        if ($result && $result->count() > 0) {
            $count = $result->count();
            $this->info("update {$count} tor exit addresses");
            $exit = 0;
        } else {
            $this->info("no update tor exit address");
            $exit = 1;
        }

        $this->info('End update tor exit address');
        return $exit;
    }
}
