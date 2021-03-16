<?php

namespace App\Http\Controllers\Api\V1\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ImageRepositoryInterface;

class Detail extends Controller
{
    public $repo;
    public $basename;

    public function __construct(ImageRepositoryInterface $repo, string $basename)
    {
        $this->repo = $repo;
        $this->basename = $basename;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $result = $this->repo->findByBasename();
        return $result;
    }
}
