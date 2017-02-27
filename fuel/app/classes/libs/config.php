<?php
class Libs_Config extends Config
{
  protected static $file = 'uproda.yml';

	//変更不可
	protected static $upload_dir = 'up';
	//変更不可
	protected static $thumbnail_dir = 'thumbnail';

	/**
	 * @see Fuel\Core\Classes\Config::load
	 **/
	public static function load($file = null, $group = null, $reload = false, $overwrite = false)
  {
    if ( $file === null )
    {
      $file = self::$file;
    }

    return parent::load($file, $group, $reload, $overwrite);
  }

	/**
	 * @see Fuel\Core\Classes\Config::get
	 **/
	public static function get($item, $default = null)
	{
		if (strcasecmp($item, 'board.dir') === 0)
		{
			return self::$upload_dir;
		}
		elseif (strcasecmp($item, 'board.thumbnail.dir') === 0)
		{
			return self::$thumbnail_dir;
		}
		else
		{
			return parent::get($item, $default);
		}
	}

}
