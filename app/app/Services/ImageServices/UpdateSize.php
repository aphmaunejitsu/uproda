<?php

namespace App\Services\ImageServices;

use App\Repositories\ImageRepositoryInterface;
use App\Repositories\FileRepositoryInterface;

class UpdateSize
{
    private $file;

    public function __construct(ImageRepositoryInterface $image, FileRepositoryInterface $file)
    {
        $this->repo = $image;
        $this->file = $file;
    }

    public function __invoke(?array $ids = null)
    {
        if (!($images = $this->repo->getByIds($ids))) {
            return null;
        }

        foreach ($images as $image) {
            $geometry = $this->file->getGeometry($image->basename, $image->ext);

            $this->repo->updateGeometry($image->id, $geometry['width'], $geometry['height']);
        }
    }
}
