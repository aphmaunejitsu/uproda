<?php

namespace App\Jobs\Image;

use App\Models\ChunkFile;
use App\Services\UploadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessMerge implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $chunkFile;
    private $upload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ChunkFile $chunkFile)
    {
        $this->chunkFile = $chunkFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UploadService $upload)
    {
        Log::info(__METHOD__, ['message' => 'start merge chunk files to a file']);
        $merged = $upload->mergeChunked($this->chunkFile);

        $upload->uploaded($merged['path'], $merged);
    }

    public function failed(Throwable $exception)
    {
        Log::error('failed merge chunked file', [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'trace'   => $exception->getTrace(),
        ]);
    }
}
