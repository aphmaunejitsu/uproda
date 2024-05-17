<?php

namespace App\Services;

use App\Models\Image;
use App\Services\Service;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @method LengthAwarePaginator paginate(?int $page, int $perPage = 50)
 * @method Image create(array $data)
 * @method bool deleteImage(string $basename, string $delkey)
 * @method Image FindByBasename(string $basename)
 * @method array updateSize(?array $ids = null)
 * @method Image generateThumbnail(string $basename)
 * @method Image setNg(string $basename)
 * @method array getImages(?array $ids = null)
 */
class ImageService extends Service
{
    protected $service = 'ImageServices';
}
