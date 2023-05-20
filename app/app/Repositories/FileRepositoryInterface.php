<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Http\UploadedFile;

interface FileRepositoryInterface
{
    public function getGeometry(string $basename, ?string $ext);
    public function getGeometryByFile(string $file);
    public function deleteByImage(Image $image): bool;

    public function saveUploadImage(string $path, string $basename, string $ext);

    public function generateThumbnail(string $file, string $basename);
    public function generateThumbnailFromStream(string $basename, string $ext);

    public function generateThumbnailGif(string $file, string $basename);
    public function generateThumbnailGifFromStream(string $basename, string $ext);

    public function orientate(string $path);
    public function changeLocation(string $path, float $latitude, float $logitude, float $altitude);

    public function deleteTmpFiles(int $mintue = 60, string $storage = 'tmp');
}
