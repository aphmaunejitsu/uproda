<?php

namespace App\Services\UploadServices;

use App\Repositories\ChunkFileRepositoryInterface;
use App\Services\UploadService;
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

        $chunk = $this->chunk->findOrCreate($data);

        if (!$chunk) {
            return null;
        }

        $size = $file->getSize();
        $path = $file->store($chunk->uuid, 'chunk');
        $result = $this->chunk->addChunk($chunk->uuid, $start, $path);

        return [
            'uuid'     => $chunk->uuid,
            'size'     => $size,
            'complete' => $is_last,
            'status'   => $result,
            'path'     => null,
            'chunk'    => $chunk,
        ];
    }
}
