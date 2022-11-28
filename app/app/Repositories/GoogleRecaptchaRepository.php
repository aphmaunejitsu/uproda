<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleRecaptchaRepository implements GoogleRecaptchaRepositoryInterface
{
    public function verify(string $token, string $ipaddr)
    {
        $url = config('roda.google.recaptcha.verify');
        if (! $url) {
            return false;
        }
        $secret = config('roda.google.recaptcha.secret');

        $post = [
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => $ipaddr,
        ];
        Log::debug(__METHOD__, compact('post'));

        $response = Http::asForm()
            ->post($url, $post);

        Log::debug(__METHOD__, $response->json());

        return $response;
    }
}
