<?php
class Libs_Settings
{
	private static $listmode = [
		'name'       => 'binjou',
		'value'      => null,
		'expiration' => 2592000, //30日
		'path'       => null,
		'domain'     => null,
		'secure'     => null,
		'httponly'   => false,
	];

	/**
	 * リストビュー
	 * @return int 0: thumbnail view, 1: list view
	 */
	public static function get_listmode()
	{
		return \Cookie::get(self::$listmode['name'], 0);
	}

	public static function set_listmode()
	{
		extract(self::$listmode);
		\Cookie::set($name, $token, $expiration, $path, $domain, $secure, $httponly);
	}
}
