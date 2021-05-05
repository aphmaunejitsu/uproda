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

        $path = $file->store($data['uuid'], 'chunk');
        $result = $this->chunk->addChunk($data['uuid'], $start, $path);

        return ['path' => $path, 'status' => $result];
    }
}
