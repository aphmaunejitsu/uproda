<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class TorRepository implements TorRepositoryInterface
{
    private $http;

    public function get()
    {
        $url = config('roda.url.tor');
        if (! $url) {
            return false;
        }

        $response = Http::get($url);
        $lines = explode("\n", $response->body());
        $ips = [];
        foreach ($lines as $line) {
            if (strpos($line, 'ExitAddress') !== false) {
                $columns = explode(' ', $line);
                $ips[] = ['ip' => $columns[1], 'is_tor' => true];
            }
        }

        return collect($ips);
    }
}
