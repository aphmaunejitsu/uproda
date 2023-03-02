<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Image\DeleteRequest;
use App\Jobs\Image\ProcessDelete;
use App\Services\ImageService;
use Exception;
use Illuminate\Support\Facades\Log;

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
        try {
            $param = $request->validated();
            extract($param);

            if (!($deletedImage = $this->service->deleteImage($basename, $delkey))) {
                return response()->json(
                    [
                        'message' => __('response.delete.failed'),
                    ],
                    500
                );
            }

            ProcessDelete::dispatchAfterResponse($deletedImage);

            return response()->json(['message' => __('response.delete.success')], 204);
        } catch (Exception $e) {
            Log::error(__METHOD__, $e);
            return response()->json(
                [
                    'message' => __('response.delete.failed'),
                ],
                500
            );
        }
    }
}
