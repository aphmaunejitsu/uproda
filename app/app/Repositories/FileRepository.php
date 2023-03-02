<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Libs\Traits\BuildImagePath;
use App\Models\Image as ModelsImage;
use Intervention\Image\Facades\Image;
use Imagick;
use App\Exceptions\FileRepositoryException;
use Exception;
use Illuminate\Support\Facades\Log;
use lsolesen\pel\PelEntryAscii;
use lsolesen\pel\PelEntryByte;
use lsolesen\pel\PelEntryRational;
use lsolesen\pel\PelIfd;
use lsolesen\pel\PelJpeg;
use lsolesen\pel\PelTag;

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

    public function getGeometryByFile(string $file)
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

    public function generateThumbnail(string $file, string $basename)
    {
        $storage = $this->getImageStorage();
        $image = new Imagick($file);
        if (strtolower($image->getImageMimeType()) === 'image/gif') {
            throw new FileRepositoryException('can not read gif file', 9999);
        }

        $thumbnail = $this->buildThumbnailPath($basename, 'jpg');
        $width = config('roda.thumbnail.width', 400);
        $height = config('roda.thumbnail.height', 400);
        $image->cropThumbnailImage($width, $height);

        return Storage::disk($this->getImageStorage())->put($thumbnail, $image->getImageBlob());
    }

    public function generateThumbnailFromStream(string $basename, string $ext)
    {
        $storage = $this->getImageStorage();
        $path = $this->buildImagePath($basename, $ext);
        $stream = Storage::disk($storage)->get($path);

        $image = new Imagick();
        $image->readImageBlob($stream);

        if (strtolower($image->getImageMimeType()) === 'image/gif') {
            throw new FileRepositoryException('can not read gif file', 9999);
        }

        $thumbnail = $this->buildThumbnailPath($basename, 'jpg');
        $width = config('roda.thumbnail.width', 400);
        $height = config('roda.thumbnail.height', 400);
        $image->cropThumbnailImage($width, $height);

        return Storage::disk($this->getImageStorage())->put($thumbnail, $image->getImageBlob());
    }

    public function generateThumbnailGif(string $file, string $basename)
    {
        $image = new Imagick($file);
        if (strtolower($image->getImageMimeType()) !== 'image/gif') {
            throw new FileRepositoryException('read only gif file', 9999);
        }

        $width = config('roda.thumbnail.width', 400);
        $height = config('roda.thumbnail.height', 400);

        $image->setFirstIterator();
        $image = $image->coalesceImages();
        do {
            $image->cropThumbnailImage($width, $height);
        } while ($image->nextImage());

        $image->optimizeimagelayers();

        $thumbnail = $this->buildThumbnailPath($basename, 'gif');
        return Storage::disk($this->getImageStorage())->put($thumbnail, $image->getImagesBlob());
    }

    public function generateThumbnailGifFromStream(string $basename, string $ext)
    {
        $storage = $this->getImageStorage();
        $path = $this->buildImagePath($basename, $ext);
        $stream = Storage::disk($storage)->get($path);

        $image = new Imagick();
        $image->readImageBlob($stream);

        if (strtolower($image->getImageMimeType()) !== 'image/gif') {
            throw new FileRepositoryException('read only gif file', 9999);
        }

        $width = config('roda.thumbnail.width', 400);
        $height = config('roda.thumbnail.height', 400);

        $image->setFirstIterator();
        $image = $image->coalesceImages();
        do {
            $image->cropThumbnailImage($width, $height);
        } while ($image->nextImage());

        $image->optimizeimagelayers();

        $thumbnail = $this->buildThumbnailPath($basename, 'gif');
        return Storage::disk($this->getImageStorage())->put($thumbnail, $image->getImagesBlob());
    }

    public function orientate(string $path)
    {
        try {
            $image = Image::make($path);
            if (! $image->exif()) {
                Log::debug('have no exif');
                return false;
            }
            $image->orientate();
            return $image->save();
        } catch (Exception $e) {
            // エラーは全て無視
            Log::warning(__METHOD__, ['message' => $e]);
            return false;
        }
    }

    public function changeLocation(string $path, float $latitude, float $logitude, float $altitude)
    {
        try {
            $im = new PelJpeg($path);

            if (! ($exif = $im->getExif())) {
                return false;
            }

            if (! ($tiff = $exif->getTiff())) {
                return false;
            }

            if (! ($ifd = $tiff->getIfd())) {
                return false;
            }


            $gps = new PelIfd(PelIfd::GPS);
            $ifd->addSubIfd($gps);

            list($hours, $minutes, $seconds) = $this->convertDecimalToDMS($latitude);
            $lati_ref = ($latitude < 0) ? 'S' : 'N';

            $gps->addEntry(new PelEntryAscii(PelTag::GPS_LATITUDE_REF, $lati_ref));
            $gps->addEntry(new PelEntryRational(PelTag::GPS_LATITUDE, $hours, $minutes, $seconds));

            list($hours, $minutes, $seconds) = $this->convertDecimalToDMS($logitude);
            $long_ref = ($logitude < 0) ? 'W' : 'E';
            $gps->addEntry(new PelEntryAscii(PelTag::GPS_LONGITUDE_REF, $long_ref));
            $gps->addEntry(new PelEntryRational(PelTag::GPS_LONGITUDE, $hours, $minutes, $seconds));

            $gps->addEntry(new PelEntryRational(PelTag::GPS_ALTITUDE, [abs($altitude), 1]));
            $gps->addEntry(new PelEntryByte(PelTag::GPS_ALTITUDE_REF, (int)($altitude < 0)));

            return file_put_contents($path, $im->getBytes());
        } catch (Exception $e) {
            // エラーは全て無視
            Log::warning(__METHOD__, ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function convertDecimalToDMS($degree)
    {
        if ($degree > 180 || $degree < -180) {
            return false;
        }

        $d = abs($degree);

        $seconds = $d * 3600;
        $degrees = floor($d);
        $seconds -= $degrees * 3600;

        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        $seconds = round($seconds * 100, 0);

        return [
            [$degrees,   1],
            [$minutes,   1],
            [$seconds, 100]
        ];
    }

    public function deleteTmpFiles(int $mintue = 60, string $storage = 'tmp')
    {
        $before = now()->subMinute($mintue)->getTimestamp();
        collect(Storage::disk($storage)->allFiles())->each(function ($file) use ($storage, $before) {
            $lastModified = Storage::disk($storage)->lastModified($file);
            if ($lastModified < $before) {
                Log::debug(__METHOD__, compact('file', 'lastModified', 'before'));

                Storage::disk($storage)->delete($file);
            }
        });
    }

}
