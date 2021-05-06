<?php

namespace App\Repositories;

use App\Models\ChunkFile;
use App\Repositories\ChunkFileRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class ChunkFileRepository implements ChunkFileRepositoryInterface
{
    private $model;

    public function __construct(ChunkFile $model)
    {
        $this->model = $model;
    }
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

    public function createByUuid(string $uuid)
    {
        return $this->model->create([
            'uuid' => $uuid
        ]);
    }

    public function getByUuid(string $uuid)
    {
        return $this->model
                    ->where('uuid', $uuid)
                    ->first();
    }
}
