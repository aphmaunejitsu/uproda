<?php

namespace App\Services\UploadServices;

use App\Libs\Traits\BuildImagePath;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\ImageHashRepositoryInterface;
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

    public function __construct(
        ImageHashRepositoryInterface $imageHash,
        FileRepositoryInterface $file
    ) {
        $this->file = $file;
        $this->imageHash = $imageHash;
    }

    public function __invoke(UploadedFile $file, array $data)
    {
        $imageData = [
            'basename' => $this->generateBasename(),
            'ext'      => strtolower($file->clientExtension()),
            'original' => $file->getClientOriginalName(),
            'mimetype' => $file->getClientMimeType(),
            'size'     => $file->getSize(),
            'delkey'   => $data['delkey'],
            'ip'       => $data['ip'],
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

        if (! ($imageHash = $this->imageHash->firstOrCreateWithImage($hash, $imageData))) {
            return null;
        }

        return $imageHash;
    }
}
