<?php

namespace App\Services\DenyIpServices;

use App\Repositories\DenyIpRepositoryInterface;
use App\Services\CacheInterface;
use App\Services\DenyIpService;

class FindTorByIp extends DenyIpService implements CacheInterface
{
    private $denyIp = null;

    public function __construct(DenyIpRepositoryInterface $denyIp)
    {
        $this->denyIp = $denyIp;
    }

    public function __invoke(string $ip)
    {
        return $this->denyIp->findByIp($ip);
    }
}
