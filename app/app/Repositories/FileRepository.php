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

    public function saveUploadImage(UploadedFile $file, string $basename, string $ext)
    {
        $storage = $this->getImageStorage();
        $original = $this->buildImagePath($basename, $ext);
        return Storage::disk($storage)->put(
            $original,
            $file
        );
    }

    public function generateThumbnail(UploadedFile $file, string $basename)
    {
        if (strtolower($file->getMimeType()) === 'image/gif') {
            throw new FileRepositoryException('can not read gif file', 9999);
        }

        $storage = $this->getImageStorage();
        $path = $this->buildThumbnailPath($basename, 'jpg');
        $image = Image::make($file);
        $image->crop(config('roda.thumbnail.width', 400), config('roda.thumbnail.height', 400));

        return Storage::disk($this->getImageStorage())->put(
            $path,
            $image->stream()
        );
    }

    public function generateThumbnailGif(UploadedFile $file, string $basename)
    {
        if (strtolower($file->getMimeType()) !== 'image/gif') {
            throw new FileRepositoryException('read only gif file', 9999);
        }

        $path = $this->buildThumbnailPath($basename, 'gif');

        $image = new Imagick($file->getRealPath());

        $image->setFirstIterator();
        do {
            $image->cropThumbnailImage(config('roda.thumbnail.width', 400), config('roda.thumbnail.height', 400));
        } while ($image->nextImage());

        $image->optimizeimagelayers();

        return Storage::disk($this->getImageStorage())->put(
            $path,
            $image
        );
    }
}
