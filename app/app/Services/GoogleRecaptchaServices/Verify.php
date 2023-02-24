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
            return $this->checkResult($result);
        }

        $response = $this->repo->verify($token, $ipaddr);


        Cache::put($uuid, $response->json(), $expire);
        return $this->checkResult($response->json());
    }

    private function checkResult($result)
    {
        // if ($result['success']) {
        //     return true;
        // }

        // foreach ($result["error-codes"] as $code) {
        //     if (strcasecmp($code, 'timeout-or-duplicate') == 0) {
        //         return true;
        //     }
        // }
        $score = config('roda.google.recaptcha.score');
        Log::debug(__METHOD__, [$result]);

        return $result['success'] && $result['score'] > $score;
    }
}
