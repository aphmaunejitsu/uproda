<?php
class Libs_Captcha
{
	const CAPTCHA_NO_ERROR = 0;
	const CAPTCHA_ERROR    = 1;

	public static function check()
	{
		if ( ! Captcha::forge('simplecaptcha')->check())
		{
			throw new Libs_Captcha_Exception('キャプチャ不一致: '.\Input::real_ip(), self::CAPTCHA_ERROR);
		}
	}

	/**
	 * キャプチャのセッションを削除する
	 **/
	public static function delete_session()
	{
		//\Config::load('simplecaptcha');
		$skn = \Config::get('simplecaptcha.session_key_name');
		\Session::delete($skn);
	}
}

class Libs_Captcha_Exception extends Libs_Exception {}
