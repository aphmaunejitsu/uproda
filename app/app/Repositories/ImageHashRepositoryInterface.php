<?php

namespace App\Repositories;

interface ImageHashRepositoryInterface
{
    public function firstOrCreateWithImage(
        string $hash,
        array $image,
        bool $ng = false,
        ?string $comment = null,
    );

    public function isNg($hash): bool;
}
