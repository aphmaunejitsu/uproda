<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Libs\Traits\BuildImagePath;
use App\Models\Image as ModelsImage;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Imagick;
use App\Exceptions\FileRepositoryException;

class FileRepository implements FileRepositoryInterface
{
    use BuildImagePath;

    public function getGeometry(string $basename, ?string $ext)
    {
        $storage = $this->getImageStorage();
        $path = $this->buildImagePath($basename, $ext);
        $stream = Storage::disk($storage)->get($path);

        $image = Image::make($stream);


        return [
            'width' => $image->width(),
            'height' => $image->height()
        ];
    }

    public function getGeometryByFile(UploadedFile $file)
    {
        $image = Image::make($file);

        return [
            'width'  => $image->width(),
            'height' => $image->height(),
        ];
    }

    public function deleteByImage(ModelsImage $image): bool
    {
        $storage = $this->getImageStorage();
        $original = $this->buildImagePath($image->basename, $image->ext);
        $thumbnail = $this->buildThumbnailPath($image->basename, $image->t_ext);

        return Storage::disk($storage)->delete([
            $original,
            $thumbnail
        ]);
    }

    public function saveUploadImage(string $path, string $basename, string $ext)
    {
        $storage = $this->getImageStorage();
        $original = $this->buildImagePath($basename, $ext);
        return Storage::disk($storage)->put(
            $original,
            file_get_contents($path)
        );
    }

    public function generateThumbnail(string $path, string $basename)
    {
        $storage = $this->getImageStorage();
        $image = Image::make($path);
        if (strtolower($image->mime()) === 'image/gif') {
            throw new FileRepositoryException('can not read gif file', 9999);
        }

        $thumbnail = $this->buildThumbnailPath($basename, 'jpg');
        $image->crop(
            config('roda.thumbnail.width', 400),
            config('roda.thumbnail.height', 400)
        );

        return Storage::disk($this->getImageStorage())->put(
            $thumbnail,
            $image->stream()
        );
    }

    public function generateThumbnailGif(string $path, string $basename)
    {
        $image = new Imagick($path);
        if (strtolower($image->getImageMimeType()) !== 'image/gif') {
            throw new FileRepositoryException('read only gif file', 9999);
        }


        $image->setFirstIterator();
        do {
            $image->cropThumbnailImage(config('roda.thumbnail.width', 400), config('roda.thumbnail.height', 400));
        } while ($image->nextImage());

        $image->optimizeimagelayers();

        $thumbnail = $this->buildThumbnailPath($basename, 'gif');
        return Storage::disk($this->getImageStorage())->put(
            $thumbnail,
            $image
        );
    }
}
