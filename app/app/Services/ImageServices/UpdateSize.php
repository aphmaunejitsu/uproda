<?php

namespace App\Services\ImageServices;

use App\Repositories\ImageRepositoryInterface;

class UpdateSize
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
