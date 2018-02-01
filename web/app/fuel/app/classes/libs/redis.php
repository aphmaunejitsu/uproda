<?php
class Libs_Redis extends \Redis_Db
{
	public static function cached($name, $method, $params, $expire = 60, $force = false)
	{
		$redis = Libs_Redis::forge();
		$key = $name.'_'.json_encode($method).'_'.json_encode($params).'_'.$expire;
		if ( $force or (! ($result = $redis->get($key))))
		{
			if ( ! ($result = call_user_func_array($method, $params)))
			{
				//データがないことをキャッシュ
				$redis->setex($key, $expire, 0);
			}
			else
			{
				$redis->setex($key, $expire, serialize($result));
			}
		}
		else
		{
			$result = unserialize($result);
		}

		return $result;
	}
}
