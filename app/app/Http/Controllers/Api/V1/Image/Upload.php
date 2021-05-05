<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Image\UploadRequest;
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

        // Content-Rangeのチェック
        $ip = $request->ip();
        $ua = $request->header('User-Agent');
        $file = $request->file('file');
        if (($cr = $this->getContentRange($request)) === null) {
            // Content-Rangeを持たないので、1回でアップロードできるサイズ
            $result = $this->uploadSigleFile($file, $data, $ip, $ua);
        } else {
            // Content-Rangeを持つので、分割アップロード
            $result = $this->uploadDividedFile($file, $data, $cr, $ip, $ua);
        }
    }

    public function uploadSigleFile(UploadedFile $file, array $data, string $ip, string $ua)
    {
        return $this->service->uploadSingleFile($file, $data + ['ip' => $ip]);
    }

    public function uploadDividedFile(UploadedFile $file, array $data, array $content_range, string $ip, string $ua)
    {
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

        $size = (int)@$content_range[3];
        $is_first = (int)@$content_range[1] === 0 ? true : false;
        $is_last = ((int)@$content_range[2] + 1) === $size ? true : false;

        return compact('size', 'is_first', 'is_last');
    }
}
