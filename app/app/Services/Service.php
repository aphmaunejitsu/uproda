<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Services\Traits\Transaction;
use App\Services\TransactionInterface;
use App\Services\CacheInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Service
{
    use Transaction;

    protected $namespace = 'App\Services';
    protected $service   = null;
    protected $model = null;
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
        } catch (\Exception $e) {
            throw new ServiceException('not found service', 9000, $e);
        }

        try {
            Log::info(sprintf('[Start Service] %s', $class), compact('user', 'class', 'arguments'));

            $cache_key = $class . '-' . md5(json_encode($arguments));
            if ($service instanceof CacheInterface) {
                if (($result = Cache::get($cache_key))) {
                    Log::info(sprintf('[Using Cache Service] %s', $class), $user + [
                        'class'  => $class,
                        'result' => $result,
                        'cache'  => true,
                        'key'    => $cache_key
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
                $expire = $service->expire ? $service->expire : config('roda.service.cache', 60);
                Cache::put($cache_key, $result, $expire);
                Log::debug('cached', compact('cache_key', 'result', 'expire'));
            }

            Log::info(sprintf('[End Service] %s', $class), $user + ['class' => $class, 'result' => $result]);
        } catch (\Exception $e) {
            if ($service instanceof TransactionInterface) {
                Log::debug('rollback');
                $service->rollback();
            }

            Log::info(sprintf('[End Service] %s, raised exception', $class), $user + ['class' => $class]);
            throw new ServiceException('raise execption', 10000, $e);
        }

        return $result;
    }
}
