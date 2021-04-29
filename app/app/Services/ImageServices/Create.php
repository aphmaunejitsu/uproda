<?php

namespace App\Services\ImageServices;

use App\Services\ImageService;
use App\Repositories\ImageRepositoryInterface;
use App\Services\TransactionInterface;

class Create extends ImageService implements TransactionInterface
{
    public function __construct(ImageRepositoryInterface $image)
    {
        $this->repo = $image;
    }

    public function __invoke(array $data)
    {
        return $this->repo->create($data);
    }
}
