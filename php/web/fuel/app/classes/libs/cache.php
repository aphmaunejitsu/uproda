<?php
class Libs_Cache extends \Cache
{
	protected static $secret = 'uproda';

	public static function cached($name, $method, $params, $expire = 60, $force = false)
	{
		try {
			$key = $name.'_'.hash_hmac('sha256', json_encode($method).json_encode($params).'_'.$expire, self::$secret);
			if ( ! $force)
			{
				$result = self::get($key);
			}
		} catch (\CacheNotFoundException $e) {
			\Log::info($e->getMessage());
			$result = 0;
		}

		if ( $force or $result === 0)
		{
			if ( ! ($result = call_user_func_array($method, $params)))
			{
			  $result = 0;
			}

			self::set($key, $result, $expire);
		}

		return $result;
	}
}
