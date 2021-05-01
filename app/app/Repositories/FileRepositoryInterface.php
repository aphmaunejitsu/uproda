<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Http\UploadedFile;

interface FileRepositoryInterface
{
    public function getGeometry(string $basename, ?string $ext);
    public function getGeometryByFile(UploadedFile $file);
    public function deleteByImage(Image $image): bool;
    public function saveUploadImage(UploadedFile $file, string $basename, string $ext);
}
