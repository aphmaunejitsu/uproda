<?php
class Libs_Csrf
{
	private static $csrf = [
		'name'       => 'sgs',
		'value'      => null,
		'expiration' => 86400,
		'path'       => null,
		'domain'     => null,
		'secure'     => null,
		'httponly'   => false,
	];

	public static function flash()
	{
		\Cookie::delete(self::$csrf['name']);
	}

	public static function get($refresh = false)
	{
		if (($old_token = \Cookie::get(self::$csrf['name'])))
		{
			$token = $old_token;
		}
		else
		{
			$token = bin2hex(openssl_random_pseudo_bytes(16));
		}

		extract(self::$csrf);
		\Cookie::set($name, $token, $expiration, $path, $domain, $secure, $httponly);
		return $token;
	}

	public static function check_token()
	{
		$value = \Input::post(\Libs_Config::get('security.csrf_token_key', 'fail'));
		$token = \Cookie::get(self::$csrf['name']);

		if ($value === $token)
		{
			return true;
		}

		self::flash();
		throw new Libs_Csrf_Exception('csrf token error', __LINE__);
	}
}

class Libs_Csrf_Exception extends \Exception
{
	public function __toString()
	{
		return __CLASS__.' ['.$this->code.'] '.$this->message;
	}
}
