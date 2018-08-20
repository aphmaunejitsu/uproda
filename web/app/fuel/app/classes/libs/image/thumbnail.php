<?php
class Libs_Image_Thumbnail extends Libs_Image
{
  const THUBMNAIL_NOTSUPPORT_EXT = 1;
  private static $_instance;
  private function __construct() {}

  protected static $ext = 'jpg';

  public static function forge($image)
  {
    if ( ! isset(self::$_instance) )
    {
      $ext = self::ext($image);
      $ext = ($ext == 'gif') ? $ext : 'jpg';
      $class = \Str::tr('Libs_Image_Thumbnail_Driver_:ext', ['ext' => $ext]);
      try {
        self::$_instance = new $class();
      } catch ( \Exception $e ) {
        \Log::error($e->getMessage());
        throw new \Libs_Image_Exception('failed create new class: ' . get_class($this));
      }
    }

    return self::$_instance;
  }

  public final function __clone()
  {
    throw new \Libs_Image_Exception('Clone is not allowed against' . get_class($this));
  }

	public static function build_url($basename, $ext)
	{
		return \Uri::create('/:image_dir/:sub_dir/:thumbnail_dir/:image.:ext',[
			'image_dir'     => \Libs_Config::get('board.dir'),
			'sub_dir'       => self::get_one_char_from_basename($basename),
			'thumbnail_dir' => \Libs_Config::get('board.thumbnail.dir'),
			'image'         => $basename,
			'ext'           => $ext,
		]);
	}

	public function create($file)
	{
		try {
      $ext = self::ext($file);
      return self::$_instance[$ext]->create();
		} catch (\Exception $e) {
			\Log::error($e);
			throw new Libs_Image_Thumbnail_Exception('fail create thumbnail', __LINE__);
		}
	}

  public function create_dir($file)
  {
    try {
      list($image_dir, $thumbnail_dir) = self::dir_infos($file);

      try {
        \File::read_dir($thumbnail_dir);
      } catch (\Exception $e) {
        $thumbnail = \Libs_Config::get('board.thumbnail.dir');
        \File::create_dir($image_dir, $thumbnail, 0777);
      }
    } catch (\Exception $e) {
      \Log::error($e);
			throw new Libs_Image_Thumbnail_Exception('fail create thumbnail dir', __LINE__);
    }
  }

  public static function dir_infos($file)
  {
			$basename = \Arr::get($file, 'basename');
      $thumbnail_dir = self::build_real_thumbnail_dir($basename);
			$image_dir = self::build_real_image_dir($basename);

      return [$image_dir, $thumbnail_dir];
  }

  public static function path_infos($file)
  {
			$basename = \Arr::get($file, 'basename');
      $ext = self::ext($file);
			$length = Libs_Config::get('board.thumbnail.length', 400);
			$image_path = self::build_real_image_path($basename, $ext);
			$save_path = self::build_real_thumbnail_path($basename, self::$_instance->get_ext());

      return [$image_path, $save_path, $length];
  }

  protected static function ext($file)
  {
    $ext =  \Str::lower(\Arr::get($file, 'ext', \Arr::get($file, 'extension', 'jpg')));

    return $ext;
  }

  public function get_ext()
  {
    return self::$_instance->get_ext();
  }
}

class Libs_Image_Thumbnail_Exception extends Libs_Exception {}
