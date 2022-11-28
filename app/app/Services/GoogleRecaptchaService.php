<?php

namespace App\Services;

use App\Services\Service;

/**
 * @method bool verify(string $uuid, string $ipaddr, string $token)
 */
class GoogleRecaptchaService extends Service
{
    protected $service = 'GoogleRecaptchaServices';
}
