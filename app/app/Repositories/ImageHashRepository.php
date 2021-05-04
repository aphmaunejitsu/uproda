<?php

namespace App\Repositories;

use App\Models\ImageHash;
use App\Exceptions\ImageHashException;

class ImageHashRepository implements ImageHashRepositoryInterface
{
    private $model;
    public function __construct(ImageHash $imageHash)
    {
        $this->model = $imageHash;
    }

    public function isNg($hash): bool
    {
        $imageHash = $this->model->where('hash', $hash)->first();
        return ($imageHash) ? ($imageHash->ng) : false;
    }

    public function firstOrCreateWithImage(
        string $hash,
        array $image,
        bool $ng = false,
        ?string $comment = null
    ) {
        $imageHash = $this->model->firstOrCreate(
            ['hash' => $hash],
            [
                'ng'      => $ng,
                'comment' => $comment
            ]
        );

        if ($imageHash->ng) {
            throw new ImageHashException('could not save image', 10000);
        }

        $imageHash->images()->create($image);
        return $imageHash;
    }
}
