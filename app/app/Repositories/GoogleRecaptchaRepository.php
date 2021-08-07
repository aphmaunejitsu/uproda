<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleRecaptchaRepository implements GoogleRecaptchaRepositoryInterface
{
    public function verify(string $token, string $ipaddr)
    {
        $url = config('roda.google.recaptcha.verify');
        Log::debug($url);
        if (! $url) {
            return false;
        }

        $response = Http::post($url, [
            'secret'   => config('roda.google.recaptcha.secret'),
            'response' => $token,
            'remoteip' => $ipaddr,
        ]);

        Log::debug($response);

        return $response;
    }
}
