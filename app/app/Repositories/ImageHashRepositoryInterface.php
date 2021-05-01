<?php

namespace App\Repositories;

interface ImageHashRepositoryInterface
{
    public function firstOrCreateWithImage(
        string $hash,
        array $image,
        bool $ng,
        ?string $comment,
    );
}
