<?php

namespace App\Jobs\Image;

use App\Models\Image;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDelete implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $image;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Image $deletedImage)
    {
        //
        $this->image = $deletedImage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(FileService $file)
    {
        //
    }
}
