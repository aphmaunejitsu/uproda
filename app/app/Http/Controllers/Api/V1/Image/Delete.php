<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
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
    public function __invoke(Request $request)
    {

    }
}
