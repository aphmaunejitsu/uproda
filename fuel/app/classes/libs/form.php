<?php
class Libs_Form extends \Form
{
	public static function csrf()
	{
		return static::hidden(\Config::get('security.csrf_token_key', 'fuel_csrf_token'), Libs_Csrf::get());
	}
}
