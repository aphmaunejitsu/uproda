<?php

namespace App\Repositories;

use App\Models\Image;

class ImageRepository implements ImageRepositoryInterface
{
    public $model;
    public function __construct(Image $image)
    {
        $this->model = $image;
    }

    public function paginate(int $perPage = 50)
    {
        return $this->model->orderby('created_at')->paginate($perPage);
    }
}
