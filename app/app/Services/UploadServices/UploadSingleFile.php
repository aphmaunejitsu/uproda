<?php

namespace App\Services\UploadServices;

use App\Libs\Traits\BuildImagePath;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\ImageHashRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Services\Traits\ImageTrait;
use App\Services\TransactionInterface;
use App\Services\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadSingleFile extends UploadService implements TransactionInterface
{
    use ImageTrait;
    use BuildImagePath;

    private $file;
    private $imageHash;
    private $image;

    public function __construct(
        ImageHashRepositoryInterface $imageHash,
        ImageRepositoryInterface $image,
        FileRepositoryInterface $file
    ) {
        $this->file = $file;
        $this->imageHash = $imageHash;
        $this->image = $image;
    }

    public function __invoke(UploadedFile $file, array $data)
    {
        $imageData = [
            'basename' => $this->generateBasename(),
            'ext'      => strtolower($file->clientExtension()),
            'original' => $file->getClientOriginalName(),
            'mimetype' => $file->getClientMimeType(),
            'size'     => $file->getSize(),
            'delkey'   => $data['delkey'] ?? null,
            'ip'       => $data['ip'] ?? null,
        ];

        // get hash
        $hash = $this->getHash($file);

        // get width and height
        $geo = $this->file->getGeometryByFile($file);
        $imageData += $geo;

        // save tmp
        $tmp = $file->store('', 'tmp');
        $tmp = Storage::disk('tmp')->path($tmp);

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
            $this->file->generateThumbnailGif($file, $imageData['basename']);
            $imageData['t_ext'] = 'gif';
        } else {
            $this->file->generateThumbnail($file, $imageData['basename']);
            $imageData['t_ext'] = 'jpg';
        }

        @unlink($file->getRealPath());
        @unlink($tmp);

        if (! ($image = $this->imageHash->firstOrCreateWithImage($hash, $imageData))) {
            return null;
        }

        return $image;
    }
}
