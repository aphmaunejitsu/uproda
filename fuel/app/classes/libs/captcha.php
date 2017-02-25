<?php
class Libs_Captcha
{
	public static function check()
	{
		if ( ! Captcha::forge('simplecaptcha')->check())
		{
			throw new Libs_Captcha_Exception('キャプチャ不一致: '.\Input::real_ip(), __LINE__);
		}
	}
}

class Libs_Captcha_Exception extends \Exception
{
	public function __toString()
	{
		return __CLASS__.' ['.$this->code.'] '.$this->message;
	}
}
