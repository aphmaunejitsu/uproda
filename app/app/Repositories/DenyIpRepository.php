<?php

namespace App\Repositories;

use App\Models\DenyIp;

class DenyIpRepository implements DenyIpRepositoryInterface
{
    private $model;
    public function __construct(DenyIp $model)
    {
        $this->model = $model;
    }

    public function findByIp(string $ip)
    {
        return $this->model->where('ip', $ip)->first();
    }

    public function deleteTorByIp(string $ip)
    {
        return $this->model
                    ->where('ip', $ip)
                    ->delete();
    }

    public function updateOrCreate(string $ip, bool $is_tor = true)
    {
        return $this->model
                    ->updateOrCreate(
                        ['ip' => $ip],
                        [
                            'ip'     => $ip,
                            'is_tor' => $is_tor
                        ]
                    );
    }

    public function getAll()
    {
        return $this->model->get();
    }
}
