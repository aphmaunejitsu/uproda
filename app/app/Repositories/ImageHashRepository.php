<?php

namespace App\Repositories;

use App\Models\ImageHash;
use App\Exceptions\ImageHashException;

class ImageHashRepository implements ImageHashRepositoryInterface
{
    private $repo;
    public function __construct(ImageHash $imageHash)
    {
        $this->repo = $imageHash;
    }

    public function firstOrCreateWithImage(
        string $hash,
        array $image,
        bool $ng = false,
        ?string $comment = null
    ) {
        $imageHash = $this->repo->firstOrCreate(
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
