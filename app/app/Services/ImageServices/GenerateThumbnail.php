<?php

namespace App\Services\ImageServices;

use App\Services\ImageService;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\FileRepositoryInterface;
use App\Services\TransactionInterface;
use App\Exceptions\ImageServiceException;

class GenerateThumbnail extends ImageService implements TransactionInterface
{
    private $file;

    public function __construct(ImageRepositoryInterface $image, FileRepositoryInterface $file)
    {
        $this->repo = $image;
        $this->file = $file;
    }

    public function __invoke(string $basename)
    {
        if (!($image = $this->repo->findByBasename($basename))) {
            throw new ImageServiceException(
                '画像が見つかりません',
                10000
            );
        }

        // save thumbnail
        if (strtolower($image['mimetype']) === 'image/gif') {
            $this->file->generateThumbnailGifFromStream($image['basename'], $image['ext']);
            $ext = 'gif';
        } else {
            $this->file->generateThumbnailFromStream($image['basename'], $image['ext']);
            $ext = 'jpg';
        }

        $image = $this->repo->updateThumbnailExt($image->id, $ext);

        return $image;
    }
}
