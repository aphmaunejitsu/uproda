<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Libs\Traits\BuildImagePath;
use App\Models\Image as ModelsImage;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Imagick;

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

    public function generateThumbnailJPG(UploadedFile $file, string $basename, string $ext)
    {
        if (strtolower($file->getClientOriginalExtension()) === 'gif') {
            throw new Exception('can not read gif file', 9999);
        }

        $storage = $this->getImageStorage();
        $thumbnail = $this->buildThumbnailPath($basename, $ext);
    }

    public function generateThumbnailGif(UploadedFile $file, string $basename, string $ext)
    {
        if (strtolower($file->getClientOriginalExtension()) !== 'gif') {
            throw new Exception('read gif file', 9999);
        }

        $path = $this->buildThumbnailPath($basename, $ext);

        $image = new Imagick($file->getRealPath());

        $image->setFirstIterator();
        do {
            $image->cropThumbnailImage(config('roda.thumbnail.width', 400), config('roda.thumbnail.height', 400));
        } while ($image->nextImage());

        $image->optimizeimagelayers();
        $path = $this->buildThumbnailPath($basename, $ext);


        return Storage::disk($this->getImageStorage())->put(
            $path,
            $image
        );
    }
}
