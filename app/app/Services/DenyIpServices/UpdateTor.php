<?php

namespace App\Services\DenyIpServices;

use App\Repositories\DenyIpRepositoryInterface;
use App\Repositories\TorRepositoryInterface;
use App\Services\DenyIpService;
use App\Services\TransactionInterface;

class UpdateTor extends DenyIpService implements TransactionInterface
{
    private $denyIp = null;
    private $tor = null;

    public function __construct(
        DenyIpRepositoryInterface $denyIp,
        TorRepositoryInterface $tor
    ) {
        $this->denyIp = $denyIp;
        $this->tor    = $tor;
    }

    public function __invoke()
    {
        if (! ($tor_list = $this->tor->get())) {
            return false;
        }

        if (($denyIps = $this->denyIp->getAll())) {
            foreach ($denyIps as $ip) {
                if (! $tor_list->search($ip->ip)) {
                    $this->denyIp->deleteTorByIp($ip->ip);
                }
            }
        }

        $results = [];
        foreach ($tor_list as $tor) {
            $results[] = $this->denyIp->updateOrCreate($tor);
        }

        return collect($results);
    }
}
