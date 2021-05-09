<?php

namespace App\Services\DenyWordServices;

use App\Repositories\DenyWordRepositoryInterface;
use App\Services\CacheInterface;
use App\Services\DenyWordService;

class Get extends DenyWordService implements CacheInterface
{
    protected $cache_key = 'GetDenyWords';
    protected $expire    = 86400; // 一日

    public function __construct(DenyWordRepositoryInterface $denyWord)
    {
        $this->repo = $denyWord;
    }

    public function __invoke()
    {
        return $this->repo->get();
    }
}
