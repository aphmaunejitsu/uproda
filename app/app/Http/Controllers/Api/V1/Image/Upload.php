<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Exceptions\ChunkFileRepositoryException;
use App\Exceptions\ImageUploadServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Image\UploadRequest;
use App\Http\Resources\ImageResource;
use App\Services\UploadService;
use Exception;
use Illuminate\Support\Facades\Log;

class Upload extends Controller
{
    private $service;
    public function __construct(UploadService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UploadRequest $request)
    {
        $data = $request->validated();
        Log::debug(__METHOD__, $data);

        $ip = $request->ip();
        $file = $request->file('file');

        $imageData = [
            'ext'      => strtolower($file->clientExtension()),
            'original' => $file->getClientOriginalName(),
            'mimetype' => $file->getClientMimeType(),
            'size'     => $file->getSize(),
            'delkey'   => $data['delkey'] ?? null,
            'ip'       => $ip ?? null,
            'uuid'     => $data['hash']
        ];

        // save to tmp
        $tmpPath = $file->store('', 'tmp');

        try {
            // have content-range ?
            $result = null;
            if (($cr = $this->getContentRange($request))) {
                // have content range
                if (($result = $this->service->chunkedUpload($file, $imageData, $cr))) {
                    Log::debug('[result]', $result);
                    if ($result['complete']) {
                        $merged = $this->service->mergeChunked($result['chunk']);
                        $merged['ip'] = $imageData['ip'];
                        $merged['original'] = $imageData['original'];
                        $merged['delkey'] = $imageData['delkey'];
                        $image = $this->service->uploaded($merged['path'], $merged);
                        return (new ImageResource($image))
                            ->response()
                            ->setStatusCode(201);
                    }

                    return response()->json([
                        'message' => 'image uploading',
                        'size'    => $result['size'],
                        'completed' => $result['complete'],
                    ]);
                } else {
                    return response()->json([
                        'message' => 'アップロードできませんでした',
                        'code'    => 1000,
                    ])->setStatusCode(400);
                }
            } else {
                // have no content-range
                if (($image = $this->service->uploaded($tmpPath, $imageData))) {
                    return (new ImageResource($image))
                        ->response()
                        ->setStatusCode(201);
                } else {
                    return response()->json([
                        'message' => 'アップロードできませんでした',
                        'code'    => 1001,
                    ])->setStatusCode(400);
                }
            }
        } catch (ChunkFileRepositoryException $e) {
            Log::error(__METHOD__, [
                'messege' => $e->getMessage(),
                'code'    => $e->getCode(),
                'trace'   => $e->getTrace(),
            ]);
            return response()->json([
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ])->setStatusCode(400);
        } catch (ImageUploadServiceException $e) {
            Log::error(__METHOD__, [
                'messege' => $e->getMessage(),
                'code'    => $e->getCode(),
                'trace'   => $e->getTrace(),
            ]);
            return response()->json([
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
            ])->setStatusCode(400);
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'messege' => $e->getMessage(),
                'code'    => $e->getCode(),
                'trace'   => $e->getTrace(),
            ]);
            return response()->json([
                'message' => 'アップロードできませんでした',
                'code'    => 1002,
            ])->setStatusCode(400);
        }
    }

    public function getContentRange(UploadRequest $request)
    {
        if (($cr = $request->header('content-range')) === null) {
            return null;
        }

        $content_range = $cr ?  preg_split('/[^0-9]+/', $cr) : null;
        if ($content_range === null) {
            return null;
        }

        $size     = (int)@$content_range[3];
        $is_first = (int)@$content_range[1] === 0 ? true : false;
        $is_last  = ((int)@$content_range[2] + 1) === $size ? true : false;
        $start    = (int)@$content_range[1];
        $end      = (int)@$content_range[2];

        return compact('size', 'start', 'end', 'is_first', 'is_last');
    }
}
