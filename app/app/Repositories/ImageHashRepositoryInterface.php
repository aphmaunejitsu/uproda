<?php

namespace App\Repositories;

interface ImageHashRepositoryInterface
{
    public function firstOrCreate(string $hash, bool $ng = false, ?string $comment = null);
}
