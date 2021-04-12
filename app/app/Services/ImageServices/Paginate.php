<?php

namespace App\Services\ImageServices;

use App\Repositories\ImageRepositoryInterface;
use App\Services\ImageService;

class Paginate extends ImageService
{
    public function __construct(ImageRepositoryInterface $image)
    {
        $this->repo = $image;
    }

    public function __invoke(int $perPage = 50)
    {
        return $this->repo->paginate($perPage);
    }
}
