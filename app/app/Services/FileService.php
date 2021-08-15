<?php

namespace App\Services;

use App\Models\Image;
use App\Services\Service;

/**
 * @method int deleteFile(Image $image)
 */
class FileService extends Service
{
    protected $service = 'FileServices';
}
