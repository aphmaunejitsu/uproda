<?php

namespace App\Services\UploadServices;

use App\Exceptions\ImageUploadServiceException;
use App\Models\ChunkFile;
use App\Repositories\ChunkFileRepositoryInterface;
use App\Services\UploadService;

class MergeChunked extends UploadService
{
    private $chunk;

    public function __construct(ChunkFileRepositoryInterface $chunk)
    {
        $this->chunk = $chunk;
    }

    public function __invoke(ChunkFile $chunkFile, string $tmpDir = 'tmp')
    {
        if (! ($merged = $this->chunk->mergeChunks($chunkFile->uuid, $tmpDir))) {
            throw new ImageUploadServiceException(
                'ファイルのマージに失敗しました',
                20001
            );
        }

        return $merged;
    }
}