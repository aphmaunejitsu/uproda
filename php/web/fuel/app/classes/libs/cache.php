<?php
class Libs_Cache extends \Cache
{
	public static function cached($name, $method, $params, $expire = 60, $force = false)
	{
		try {
			if ( ! $force)
			{
				$result = self::get($name);
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

			self::set($name, $result, $expire);
		}

		return $result;
	}
}
