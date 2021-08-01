<?php

namespace App\Repositories;

use App\Exceptions\ChunkFileRepositoryException;
use App\Models\ChunkFile;
use App\Repositories\ChunkFileRepositoryInterface;
use App\Services\Traits\ImageTrait;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class ChunkFileRepository implements ChunkFileRepositoryInterface
{
    use ImageTrait;

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

    public function mergeChunks(string $uuid, string $storage = 'chunk')
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

        if (Storage::disk($storage)->put($uuid, $content)) {
            $mimetype = Storage::disk($storage)->mimeType($uuid);
            $ext = $this->mimeTypeToExtension($mimetype, false);

            if ($ext === null) {
                throw new ChunkFileRepositoryException('アップロードできないタイプのファイルです', 10000);
            }

            $size = Storage::disk($storage)->size($uuid);
            $mbytes = config('roda.upload.max') * 1024 * 1024;
            if ($size > $mbytes) {
                throw new ChunkFileRepositoryException("アップロードできるサイズは {$mbytes}MB までです", 10002);
            }

            return [
                'size'     => $size,
                'uuid'     => $uuid,
                'path'     => $uuid,
                'mimetype' => $mimetype,
                'ext'      => $ext,
            ];
        } else {
            return null;
        }
    }
}
