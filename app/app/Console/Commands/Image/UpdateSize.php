<?php

namespace App\Console\Commands\Image;

use Illuminate\Console\Command;
use App\Services\ImageService;

class UpdateSize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:update-size {id?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update image size {id?*}';

    /**
     * Execute the console command.
     */
    public function handle(ImageService $image)
    {
        $this->info('Start Update Image Geometry');
        $images = $image->getImages($this->argument('id'));
        if (!$images) {
            $this->info('there is no target');
            $this->info('Finish Update Image Geometry');
            return 1;
        }

        $progress = $this->output->createProgressBar($images->count());
        $progress->start();
        foreach ($images as $i) {
            if ($i->width === null or $i->height === null) {
                $image->setSize($i);
            }
            $progress->advance();
        }

        $progress->finish();
    }
}
