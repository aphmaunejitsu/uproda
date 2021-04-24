<?php

namespace App\Services\ImageServices;

use App\Repositories\ImageRepositoryInterface;
use App\Services\ImageService;

class FindByBasename extends ImageService
{
    public function __construct(ImageRepositoryInterface $image)
    {
        $this->repo = $image;
    }

    public function __invoke(string $basename)
    {
        return $this->repo->findByBasename($basename);
    }
}
