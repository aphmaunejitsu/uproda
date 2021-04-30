<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Upload extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Content-Rangeのチェック
        if (($cr = $this->checkContentRange($request)) === null) {
            // Content-Rangeを持たないので、1回でアップロードできるサイズ
        } else {
            // Content-Rangeを持つので、分割アップロード
        }
    }

    public function checkContentRange(Request $request)
    {
        if (($cr = $request->header('content-range') === null)) {
            return [];
        }

        $content_range = $cr ?  preg_split('/[^0-9]+/', $cr) : null;
        if ($content_range === null) {
            return [];
        }

        $size = (int)@$content_range[3];
        $is_first = (int)@$content_range[1] === 0 ? true : false;
        $is_last = ((int)@$content_range[2] + 1) === $size ? true : false;

        return compact('size', 'is_first', 'is_last');
    }
}
