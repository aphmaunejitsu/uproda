<?php

namespace App\Services\UploadServices;

use App\Exceptions\ImageUploadServiceException;
use App\Libs\Traits\BuildImagePath;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\ImageHashRepositoryInterface;
use App\Services\Traits\ImageTrait;
use App\Services\TransactionInterface;
use App\Services\UploadService;
use Illuminate\Support\Facades\Storage;

class Uploaded extends UploadService implements TransactionInterface
{
    use ImageTrait;
    use BuildImagePath;

    private $file;
    private $imageHash;

    public function __construct(
        ImageHashRepositoryInterface $imageHash,
        FileRepositoryInterface $file
    ) {
        $this->file = $file;
        $this->imageHash = $imageHash;
    }

    public function __invoke(string $file, array $imageData)
    {
        $imageData['basename'] = $this->generateBasename();
        $tmp = Storage::disk('tmp')->path($file);

        // get hash
        $hash = $this->getHash($tmp);

        // get extension
        if (($ext = $this->mimeTypeToExtension($imageData['mimetype'], false)) === null) {
            throw new ImageUploadServiceException('アップロードできないタイプのファイルです', 10000);
        }

        $imageData['ext'] = $ext;

        // get width and height
        $geo = $this->file->getGeometryByFile($tmp);
        $imageData += $geo;

        // Fixed Rotation
        $this->file->orientate($tmp);

        // move to fake location
        $this->file->changeLocation(
            $tmp,
            config('roda.fake.location.latitude', 39.0291172),
            config('roda.fake.location.longitude', 125.6719013),
            config('roda.fake.location.altitude', 12)
        );

        // save image
        $this->file->saveUploadImage($tmp, $imageData['basename'], $imageData['ext']);

        // save thumbnail
        if (strtolower($imageData['mimetype']) === 'image/gif') {
            $this->file->generateThumbnailGif($tmp, $imageData['basename']);
            $imageData['t_ext'] = 'gif';
        } else {
            $this->file->generateThumbnail($tmp, $imageData['basename']);
            $imageData['t_ext'] = 'jpg';
        }

        // @unlink($tmp);

        if (! ($image = $this->imageHash->firstOrCreateWithImage($hash, $imageData))) {
            return null;
        }

        return $image;
    }
}
