<?php

namespace App\Repositories;

use App\Exceptions\ChunkFileRepositoryException;
use App\Models\ChunkFile;
use App\Repositories\ChunkFileRepositoryInterface;
use App\Services\Traits\ImageTrait;
use App\Libs\Traits\BuildImagePath;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class ChunkFileRepository implements ChunkFileRepositoryInterface
{
    use ImageTrait;
    use BuildImagePath;

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

    public function findOrCreate(array $chunk)
    {
        return $this->model->firstOrCreate(
            ['uuid' => $chunk['uuid']],
            $chunk
        );
    }
}
