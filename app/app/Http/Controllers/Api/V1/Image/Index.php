<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ImageRepositoryInterface;

class Index extends Controller
{
    public $repo;

    public function __construct(ImageRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $result = $this->repo->paginate(100);
        return $result;
    }
}
