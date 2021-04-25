<?php

namespace App\Repositories;

use App\Models\Image;

interface FileRepositoryInterface
{
    public function getGeometry(string $basename, ?string $ext);
    public function deleteByImage(Image $image): bool;
}
