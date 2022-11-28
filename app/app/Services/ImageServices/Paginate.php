<?php

namespace App\Services\ImageServices;

use App\Repositories\ImageRepositoryInterface;
use App\Services\CacheInterface;
use App\Services\ImageService;

class Paginate extends ImageService implements CacheInterface
{
    protected $expire = 5;
    public function __construct(ImageRepositoryInterface $image)
    {
        $this->repo = $image;
    }

    public function __invoke(?int $page, int $perPage = 50)
    {
        return $this->repo->paginate($perPage);
    }
}
