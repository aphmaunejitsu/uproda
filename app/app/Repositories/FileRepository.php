<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
use App\Libs\Traits\BuildImagePath;
use App\Models\Image as ModelsImage;
use Illuminate\Http\UploadedFile;
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

    public function generateThumbnail(UploadedFile $file, string $basename)
    {
        $storage = $this->getImageStorage();
        $image = Image::make($file->getRealPath());
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

    public function generateThumbnailGif(UploadedFile $file, string $basename)
    {
        $image = new Imagick($file->getRealPath());
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

    public function orientate(string $path): bool
    {
        try {
            $image = Image::make($path);
            $image->orientate();
            return $image->save();
        } catch (Exception $e) {
            // エラーは全て無視
            Log::warning(__METHOD__, $e);
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
}
