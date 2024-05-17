<?php

namespace App\Console\Commands\Image;

use Illuminate\Console\Command;
use App\Services\ImageService;

class SetNG extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:setNG {basename} {--ng=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '画像をNGにする';

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
    public function handle(ImageService $service)
    {
        $this->info('Start Set NG image');
        $result = $service->setNg($this->argument('basename'));
        $this->info('Finished Set NG image');
        return $result;
    }
}
