<?php

namespace App\Services\FileServices;

use App\Models\Image;
use App\Repositories\FileRepositoryInterface;
use App\Services\FileService;

class DeleteFile extends FileService
{
    public function __construct(FileRepositoryInterface $file)
    {
        $this->repo = $file;
    }

    public function __invoke(Image $image)
    {
        return $this->repo->deleteByImage($image);
    }
}
