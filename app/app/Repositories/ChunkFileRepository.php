<?php

namespace App\Repositories;

use App\Repositories\ChunkFileRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class ChunkFileRepository implements ChunkFileRepositoryInterface
{
    public function addChunk(string $uuid, int $start, string $file)
    {
        return Redis::zadd($uuid, $start, $file);
    }

    public function getChunks(string $uuid)
    {
        return Redis::zrange($uuid, 0, -1);
    }

    public function remove(string $uuid)
    {
        return Redis::del($uuid);
    }
}
