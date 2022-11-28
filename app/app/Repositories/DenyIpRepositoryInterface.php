<?php

namespace App\Repositories;

interface DenyIpRepositoryInterface
{
    public function findByIp(string $ip);
    public function updateOrCreate(string $ip, bool $is_tor);
    public function deleteTorByIp(string $ip);
    public function getAll();
}
