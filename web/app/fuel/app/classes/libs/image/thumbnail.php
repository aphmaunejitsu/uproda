<?php
class Libs_Image_Thumbnail extends Libs_Image
{
  const THUBMNAIL_NOTSUPPORT_EXT = 1;
  private static $_instance = [];
  private function __construct() {}

  public static function singleton($image)
  {
    $ext = \Str::lower(\Arr::get($image, 'ext', 'jpg'));
    if ($ext === 'jpeg')
    {
      $ext = 'jpg';
    }

    if ($ext !== 'gif')
    {
      $ext = 'jpg';
    }

    if ( ! isset(self::$_instance[$ext]) )
    {
      $class = \Str::tr('Libs_Image_Thumbnail_:ext', ['ext' => $ext]);
      try {
        self::$_instance[$ext] = new $class();
      } catch ( \Exception $e ) {
        \Log::error($e);
        throw new \Libs_Image_Exception('failed create new class: ' . get_class($this));
      }
    }

    return self::$_instance[$ext];
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
			$basename = \Arr::get($file, 'basename');
			$length = Libs_Config::get('board.thumbnail.length', 400);
			$image_path = self::build_real_image_path($basename, \Arr::get($file, 'ext'));
			$save_path = self::build_real_thumbnail_path($basename, \Arr::get($file, 'ext'));
			$image = \Image::load($image_path)->crop_resize($length, $length);

			$image->save($save_path);
		} catch (\Exception $e) {
			\Log::error($e);
			throw new Libs_Image_Thumbnail_Exception('fail create thumbnail', __LINE__);
		}
	}

  public function create_dir($file)
  {
    try {
			$basename = \Arr::get($file, 'basename');
      $thumbnail_dir = self::build_real_thumbnail_dir($basename);
			$image_dir = self::build_real_image_dir($basename);

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
}

class Libs_Image_Thumbnail_Exception extends Libs_Exception {}
