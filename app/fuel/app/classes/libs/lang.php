<?php
class Libs_Lang extends \Lang
{
	protected static $file = 'uproda.yml';

	public static function load($file = null, $group = null, $language = null, $overwrite = false, $reload = false)
	{
		if ($file === null)
		{
			$file = self::$file;
		}

		return parent::load($file, $group, $language, $overwrite, $reload);
	}
}
