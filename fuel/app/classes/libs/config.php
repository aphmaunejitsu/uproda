<?php
class Libs_Config extends Config
{
  protected static $file = 'uproda.yml';

	public static function load($file = null, $group = null, $reload = false, $overwrite = false)
  {
    if ( $file === null )
    {
      $file = self::$file;
    }

    return parent::load($file, $group, $reload, $overwrite);
  }

}
