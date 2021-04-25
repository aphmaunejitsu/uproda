<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\image\DeleteRequest;
use App\Services\ImageService;
use Illuminate\Http\Request;

class Delete extends Controller
{
    public $service;
    public function __construct(ImageService $image)
    {
        $this->service = $image;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(DeleteRequest $request)
    {
        $param = $request->validated();
        extract($param);

        if (($deletedImage = $this->service->deleteImage($basename, $delkey))) {
            return response()->json(
                [
                    'message' => __('response.delete.failed'),
                ],
                404
            );
        }

        return response()->json(['message' => __('response.delete.success'), 204);
    }
}
