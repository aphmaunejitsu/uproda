<?php

namespace App\Console\Commands\Image;

use App\Services\ImageService;
use Illuminate\Console\Command;

class GenerateThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:generateThumbnail {basename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'サムネイルの再生成 {basename}';

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
        $this->info('Start update tor exit address');

        $service->generateThumbnail($this->argument('basename'));

        $this->info('End update tor exit address');
        return 0;
    }
}
