<?php

namespace App\Services\GoogleRecaptchaServices;

use App\Repositories\GoogleRecaptchaRepositoryInterface;
use App\Services\GoogleRecaptchaService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Verify extends GoogleRecaptchaService
{
    public function __construct(
        GoogleRecaptchaRepositoryInterface $repo
    ) {
        $this->repo = $repo;
    }

    public function __invoke(string $uuid, string $ipaddr, string $token)
    {
        if (($result = Cache::get($uuid))) {
            return $result->success;
        }

        $response = $this->repo->verify($ipaddr, $token);

        Cache::put($uuid, $response);
        return $response->success;
    }
}
