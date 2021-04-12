<?php

namespace App\Console\Commands;

use App\Services\ImageService;
use Illuminate\Console\Command;

class UpdateImageSize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:updateSize {id?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update image size {id?*}';

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
    public function handle(ImageService $image)
    {
        $this->info('Start Update Image Geometry');
        if (! ($result = $image->updateSize($this->argument('id')))) {
            $this->info('there is no target');
            $this->info('Finish Update Image Geometry');
            return 1;
        }

        $this->info('Update ' . count($result) . ' Images.');
        $this->info('Finish Update Image Geometry');
        return 0;
    }
}
