<?php

namespace App\Services\UploadServices;

use App\Services\UploadService;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redis;

class ChunkedUploadStart extends UploadService
{
    private $file;
    private $image;

    public function __construct(
        ImageRepositoryInterface $image,
        FileRepositoryInterface $file
    ) {
        $this->file = $file;
        $this->image = $image;
    }

    public function __invoke(UploadedFile $file, array $content_range)
    {
        extract($content_range);
    }
}
