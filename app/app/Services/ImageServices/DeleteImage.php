<?php

namespace App\Services\ImageServices;

use App\Services\ImageService;
use App\Repositories\ImageRepositoryInterface;
use App\Services\TransactionInterface;

class DeleteImage extends ImageService implements TransactionInterface
{
    private $file;

    public function __construct(ImageRepositoryInterface $image)
    {
        $this->repo = $image;
    }

    public function __invoke(string $basename, string $delkey)
    {
        return $this->repo->deleteByBasename($basename, $delkey);
    }
}
