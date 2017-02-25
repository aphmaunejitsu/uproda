<?php
class Libs_Captcha
{
	public static function check()
	{
		if ( ! Captcha::forge('simplecaptcha')->check())
		{
			throw new Libs_Captcha_Exception();
		}
	}
}

class Libs_Captcha_Exception extends \Exception {}
