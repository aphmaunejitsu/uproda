<?php

namespace App\Services\UploadServices;

use App\Repositories\ChunkFileRepositoryInterface;
use App\Services\UploadService;
use App\Repositories\FileRepositoryInterface;
use Illuminate\Http\UploadedFile;

class ChunkedUpload extends UploadService
{
    private $chunk;

    public function __construct(ChunkFileRepositoryInterface $chunk)
    {
        $this->chunk = $chunk;
    }

    public function __invoke(UploadedFile $file, array $data, array $content_range)
    {
        // $size, $is_first, $is_last, $start, $end
        extract($content_range);

        if ($is_first) {
            $chunk = $this->chunk->createByUuid($data['uuid']);
        } else {
            $chunk = $this->chunk->getByUuid($data['uuid']);
        }

        if (!$chunk) {
            return null;
        }

        $size = $file->getSize();
        $path = $file->store($chunk->uuid, 'chunk');
        $result = $this->chunk->addChunk($chunk->uuid, $start, $path);

        if (!$is_last) {
            return [
                'uuid'     => $chunk->uuid,
                'size'     => $size,
                'complete' => false,
                'status'   => $result,
                'path'     => null,
            ];
        }

        if (! ($merged = $this->chunk->mergeChunks($chunk->uuid, 'tmp'))) {
            return null;
        }

        return [
            'uuid'     => $chunk->uuid,
            'size'     => $merged['size'],
            'complete' => true,
            'status'   => true,
            'path'     => $merged['path']
        ];
    }
}
