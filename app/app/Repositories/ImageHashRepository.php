<?php

namespace App\Repositories;

use App\Models\ImageHash;

class ImageHashRepository implements ImageHashRepositoryInterface
{
    private $repo;
    public function __construct(ImageHash $imageHash)
    {
        $this->repo = $imageHash;
    }

    public function firstOrCreate(
        string $hash,
        bool $ng = false,
        ?string $comment = null
    ) {
        return $this->repo->firstOrCreate(
            ['hash' => $hash],
            [
                'ng'      => $ng,
                'comment' => $comment
            ]
        );
    }
}
