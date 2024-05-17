
<?php

namespace App\Services\ImageServices;

use App\Services\ImageService;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\FileRepositoryInterface;
use App\Services\TransactionInterface;
use App\Exceptions\ImageServiceException;

class SetNg extends ImageService implements TransactionInterface
{
    private $file;
    public function __construct(ImageRepositoryInterface $image, FileRepositoryInterface $file)
    {
        $this->repo = $image;
        $this->file = $file;
    }

    public function __invoke(string $basename)
    {
        if (($image =$this->repo->setNgByBasename($basename)) === null) {
           throw new ImageServiceException('画像をNGに設定できませんでした', 10000);
        }

        if (!$image->imageHash->ng) {
            throw new ImageServiceException('画像をNGに設定できませんでした', 100001);
        }

        return $this->file->deleteByImage($image);
    }
}
