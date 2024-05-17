<?php

namespace App\Services\ImageServices;

use App\Services\ImageService;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\FileRepositoryInterface;
use App\Services\Traits\TransactionTrait;
use App\Services\TransactionInterface;

class SetSize extends ImageService implements TransactionInterface
{
    use TransactionTrait;

    private $file;

    public function __construct(ImageRepositoryInterface $image, FileRepositoryInterface $file)
    {
        $this->repo = $image;
        $this->file = $file;
    }

    public function __invoke($image)
    {
        $geometry = $this->file->getGeometry($image->basename, $image->ext);
        return $this->repo->updateGeometry($image->id, $geometry['width'], $geometry['height']);
    }
}
