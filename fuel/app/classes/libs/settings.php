<?php
class Libs_Settings
{
	private static $listmode = [
		'name'       => 'binjou',
		'value'      => 0,
		'expiration' => 2592000, //30日
		'path'       => null,
		'domain'     => null,
		'secure'     => true,
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

	public static function set_listmode($listmode = 0)
	{
		extract(self::$listmode);
		$value = $listmode;
		\Cookie::set($name, $value, $expiration, $path, $domain, $secure, $httponly);
	}
}
