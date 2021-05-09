<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Services\Traits\TransactionTrait;
use App\Services\TransactionInterface;
use App\Services\CacheInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Exception;

class Service
{
    use TransactionTrait;

    protected $namespace = 'App\Services';
    protected $service   = null;
    protected $model = null;

    protected $cache_key = null;
    protected $expire = null;

    protected $repo;

    public function __call(string $name, array $arguments)
    {
        try {
            $user = Auth::check() ? ['user' => Auth::user()] : ['user' => 'no login user'];

            $class = sprintf(
                '%s\%s\%s',
                $this->namespace,
                $this->service,
                ucfirst($name)
            );

            $service = App::make($class);
        } catch (Exception $e) {
            Log::info(
                sprintf('[End service] %s', $class),
                $user + ['class' => $class, 'error' => $e->getMessage()]
            );
            throw new ServiceException("not found service: {$class}", 9000, $e);
        }

        try {
            Log::info(
                sprintf('[Start Service] %s', $class),
                compact('user', 'class', 'arguments')
            );

            $cache_key = null;

            if ($service instanceof CacheInterface) {
                $key = $class . '-' . md5(json_encode($arguments));
                $cache_key = $service->cache_key ?? $key;

                if (($result = Cache::get($cache_key))) {
                    Log::info(sprintf('[Using Cache Service] %s', $class), $user + [
                        'class'  => $class,
                        'cache'  => true,
                        'key'    => $cache_key,
                        'result' => $result,
                    ]);
                    return $result;
                }
            }

            if ($service instanceof TransactionInterface) {
                Log::debug('beginTransaction');
                $service->beginTransaction();
            }

            $result = call_user_func_array($service, $arguments);

            if ($service instanceof TransactionInterface) {
                Log::debug('commit');
                $service->commit();
            }

            if ($service instanceof CacheInterface) {
                $expire = $service->expire ?? config('roda.service.cache', 60);
                Cache::put($cache_key, $result, $expire);
                Log::debug(
                    'cached',
                    compact(
                        'cache_key',
                        'result',
                        'expire'
                    )
                );
            }

            Log::info(
                sprintf('[End Service] %s', $class),
                $user + ['class' => $class, 'result' => $result]
            );
        } catch (Exception $e) {
            if ($service instanceof TransactionInterface) {
                Log::debug('rollback');
                $service->rollback();
            }

            Log::info(
                sprintf('[End Service] %s, raised exception', $class),
                $user + ['class' => $class, 'error' => $e->getMessage()]
            );

            throw $e;
        }

        return $result;
    }
}
