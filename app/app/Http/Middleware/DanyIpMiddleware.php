<?php

namespace App\Http\Middleware;

use App\Exceptions\DenyIpException;
use App\Services\DenyIpService;
use Closure;
use Illuminate\Http\Request;

class DanyIpMiddleware
{
    private $service;
    public function __construct(DenyIpService $denyIpService)
    {
        $this->service = $denyIpService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->service->findTorByIp($request->ip())) {
            throw new DenyIpException('アップロードできません', 10000);
        }

        return $next($request);
    }
}
