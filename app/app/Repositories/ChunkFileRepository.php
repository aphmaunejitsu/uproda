<?php

namespace App\Repositories;

use App\Models\ChunkFile;
use App\Repositories\ChunkFileRepositoryInterface;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

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

    public function mergeChunks(string $uuid)
    {
        $chunks = $this->getChunks($uuid);
        $content = null;
        foreach ($chunks as $chunk) {
            $content .= Storage::disk('chunk')->get($chunk);
        }

        if ($content === null) {
            return null;
        }

        Storage::disk('chunk')->deleteDirectory($uuid);
        $this->remove($uuid);

        if (Storage::disk('chunk')->put($uuid, $content)) {
            $size = Storage::disk('chunk')->size($uuid);
            $mimetype = Storage::disk('chunk')->mimeType($uuid);
            return [
                'size'     => $size,
                'uuid'     => $uuid,
                'path'     => Storage::disk('chunk')->path($uuid),
                'mimetype' => $mimetype,
            ];
        } else {
            return null;
        }
    }
}
