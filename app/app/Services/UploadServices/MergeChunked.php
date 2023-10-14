<?php

namespace App\Services\UploadServices;

use App\Exceptions\ImageUploadServiceException;
use App\Models\ChunkFile;
use App\Repositories\ChunkFileRepositoryInterface;
use App\Services\UploadService;
use App\Libs\Traits\BuildImagePath;
use App\Services\Traits\ImageTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MergeChunked extends UploadService
{
    use BuildImagePath;
    use ImageTrait;

    private $chunk;

    public function __construct(ChunkFileRepositoryInterface $chunk)
    {
        $this->chunk = $chunk;
    }

    public function __invoke(ChunkFile $chunkFile, string $tmpDir = 'chunk')
    {
        if (!($chunks = $this->chunk->getChunks($chunkFile->uuid))) {
            throw new ImageUploadServiceException('ファイルが見つかりません', 20001);
        }

        $content = null;
        foreach ($chunks as $chunk) {
            $content .= Storage::disk($tmpDir)->get($chunk);
            Storage::disk($tmpDir)->delete($chunk);
        }

        if ($content === null) {
            throw new ImageUploadServiceException('ファイルを結合できませんでした', 20002);
        }

        $merged = $this->buildMergedPath($chunkFile->uuid);
        if (! Storage::disk($tmpDir)->put($merged, $content)) {
            throw new ImageUploadServiceException('ファイルを保存できませんでした', 20003);
        }

        $mType = Storage::disk($tmpDir)->mimeType($merged);
        if (($ext = $this->mimeTypeToExtension($mType, false)) === null) {
            throw new ImageUploadServiceException('アップロードできないタイプのファイルです', 20004);
        }

        $size = Storage::disk($tmpDir)->size($merged);

        // 設定なしは10mb
        $kbytes = config('roda.upload.max', 10240);
        if ($size > ($kbytes * 1024)) {
            throw new ImageUploadServiceException('アップロードできるサイズは{$kbytes}KBまでです', 20005);
        }

        $result = [
            'size'     => $size,
            'uuid'     => $chunkFile->uuid,
            'path'     => $chunkFile->uuid,
            'mimetype' => $mType,
            'ext'      => $ext,
        ];

        return $result;
    }
}
