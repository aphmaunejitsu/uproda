<?php

namespace App\Services\DenyIpServices;

use App\Repositories\DenyIpRepository;
use App\Repositories\DenyIpRepositoryInterface;
use App\Services\DenyIpService;
use App\Services\TransactionInterface;

class BulkInsert extends DenyIpService implements TransactionInterface
{
    public function __construct(DenyIpRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(array $data)
    {
    }
}
