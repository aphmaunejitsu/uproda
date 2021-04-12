<?php

namespace App\Services\ImageServices;

use App\Services\ImageService;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\FileRepositoryInterface;
use App\Services\TransactionInterface;
use Illuminate\Support\Facades\Log;

class UpdateSize extends ImageService implements TransactionInterface
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

        $result = [];
        foreach ($images as $image) {
            $geometry = $this->file->getGeometry($image->basename, $image->ext);

            $result[] = $this->repo->updateGeometry($image->id, $geometry['width'], $geometry['height']);
        }

        return $result;
    }
}
