<?php

namespace App\Services;

use App\Models\Image;
use App\Services\Service;
use Illuminate\Http\UploadedFile;

/**
 * @method array chunkedUpload(UploadedFile $file, array $data, array $content_range)
 * @method Image uploaded(string $file, array $imageData)
 *
 */
class UploadService extends Service
{
    protected $service = 'UploadServices';
}
