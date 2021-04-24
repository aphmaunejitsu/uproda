<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Resources\ImageResource;

class Detail extends Controller
{
    public $service;

    public function __construct(ImageService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, string $basename)
    {
        $result = $this->service->findByBasename($basename);
        if (! $result) {
            return response()->json(['message' => 'not found'], 404);
        }

        return new ImageResource($result);
    }
}
