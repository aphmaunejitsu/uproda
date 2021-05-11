<?php

namespace App\Services;

use App\Models\DenyIp;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

/**
 * @method Collection updateTor()
 * @method DenyIp findTorByIp(string $ip)
 */
class DenyIpService extends Service
{
    protected $service = 'DenyIpServices';
}
