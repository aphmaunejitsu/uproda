<?php

namespace App\Repositories;

interface ChunkFileRepositoryInterface
{
    public function addChunk(string $uuid, int $start, string $file);
    public function getChunks(string $uuid);
    public function remove(string $uuid);

    public function createByUuid(string $uuid);
    public function getByUuid(string $uuid);
}
