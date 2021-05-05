<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Image\UploadRequest;
use App\Http\Resources\ImageResource;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

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

        $ip = $request->ip();
        $ua = $request->header('User-Agent');
        $file = $request->file('file');

        // have content-range ?
        if (($cr = $this->getContentRange($request)) === null) {
            // have no content-range
            $result = $this->service->uploadSingleFile(
                $file,
                $data + ['ip' => $ip]
            );

            if ($result) {
                return (new ImageResource($result))
                    ->response()
                    ->setStatusCode(201);
            } else {
                return response()->json([
                    'message' => 'アップロードできませんでした'
                ])->setStatusCode(400);
            }
        }

        // have content range
        extract($cr);
        if ($is_last) {
        } else {
            if ($this->service->chunkUpload($file, $data, $cr)) {
            } else {
            }
        }
    }

    public function getContentRange(UploadRequest $request)
    {
        if (($cr = $request->header('content-range') === null)) {
            return null;
        }

        $content_range = $cr ?  preg_split('/[^0-9]+/', $cr) : null;
        if ($content_range === null) {
            return null;
        }

        $size     = (int)@$content_range[3];
        $is_first = (int)@$content_range[1] === 0 ? true : false;
        $is_last  = ((int)@$content_range[2] + 1) === $size ? true : false;
        $start    = (int)@content_range[1];
        $end      = (int)@content_range[2];

        return compact('size', 'start', 'end', 'is_first', 'is_last');
    }
}
