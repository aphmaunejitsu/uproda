<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImageService;

class Index extends Controller
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
    public function __invoke(Request $request)
    {
        $result = $this->service->paginate(100);
        return $result;
    }
}