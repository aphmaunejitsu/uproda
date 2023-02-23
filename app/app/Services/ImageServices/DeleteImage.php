<?php

namespace App\Services\ImageServices;

use App\Services\ImageService;
use App\Repositories\ImageRepositoryInterface;
use App\Services\TransactionInterface;

class DeleteImage extends ImageService implements TransactionInterface
{
    public function __construct(ImageRepositoryInterface $image)
    {
        $this->repo = $image;
    }

    public function __invoke(string $basename, string $delkey)
    {
        // return $this->repo->deleteByBasename($basename, $delkey);
        if (!($image = $this->repo->findByBasename($basename))) {
            return null;
        }

        if ($delkey !== config('roda.delkey')) {
            if ($delkey !== $image->delkey) {
                return null;
            }
        }

        $image->delete();
        return $image;
    }
}
