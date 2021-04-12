<?php

namespace App\Repositories;

interface FileRepositoryInterface
{
    public function getGeometry(string $basename, ?string $ext);
}
