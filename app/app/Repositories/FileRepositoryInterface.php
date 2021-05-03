<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Http\UploadedFile;

interface FileRepositoryInterface
{
    public function getGeometry(string $basename, ?string $ext);
    public function getGeometryByFile(UploadedFile $file);
    public function deleteByImage(Image $image): bool;
    public function saveUploadImage(string $path, string $basename, string $ext);
    public function generateThumbnail(string $path, string $basename);
    public function generateThumbnailGif(string $path, string $basename);
}
