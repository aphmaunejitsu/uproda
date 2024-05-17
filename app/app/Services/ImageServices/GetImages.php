<?php

namespace App\Services\ImageServices;

use App\Services\ImageService;
use App\Repositories\ImageRepositoryInterface;

class GetImages extends ImageService
{
    public function __construct(ImageRepositoryInterface $image)
    {
        $this->repo = $image;
    }

    public function __invoke(?array $ids = null)
    {
        if (!($images = $this->repo->getByIds($ids))) {
            return null;
        }

        return $images;
    }
}
