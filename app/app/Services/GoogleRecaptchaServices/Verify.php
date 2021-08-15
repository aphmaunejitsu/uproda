<?php

namespace App\Services\GoogleRecaptchaServices;

use App\Repositories\GoogleRecaptchaRepositoryInterface;
use App\Services\GoogleRecaptchaService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class Verify extends GoogleRecaptchaService
{
    public function __construct(
        GoogleRecaptchaRepositoryInterface $repo
    ) {
        $this->repo = $repo;
    }

    public function __invoke(?string $uuid, ?string $ipaddr, string $token, int $expire = 300)
    {
        if (is_null($uuid)) {
            return false;
        }

        if (is_null($ipaddr)) {
            return false;
        }

        if (($result = Cache::get($uuid))) {
            return $result['success'];
        }

        $response = $this->repo->verify($token, $ipaddr);

        Cache::put($uuid, $response->json(), $expire);
        return $response->json()['success'];
    }
}
