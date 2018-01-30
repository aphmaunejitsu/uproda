<?php
class Libs_Image_Thumbnail extends Libs_Image
{
	public static function build_url($basename)
	{
		return \Uri::create('/:image_dir/:sub_dir/:thumbnail_dir/:image.jpg',[
			'image_dir'     => \Libs_Config::get('board.dir'),
			'sub_dir'       => self::get_one_char_from_basename($basename),
			'thumbnail_dir' => \Libs_Config::get('board.thumbnail.dir'),
			'image'         => $basename,
		]);
	}

	public static function create($file)
	{
		try {
			$basename = \Arr::get($file, 'basename');
			$image_dir = self::build_real_image_dir($basename);
			$thumbnail_dir = self::build_real_thumbnail_dir($basename);

			try {
				\File::read_dir($thumbnail_dir);
			} catch (\Exception $e) {
				$thumbnail = \Libs_Config::get('board.thumbnail.dir');
				\File::create_dir($image_dir, $thumbnail, 0777);
			}

			$image_path = self::build_real_image_path($basename, \Arr::get($file, 'ext'));

			$length = Libs_Config::get('board.thumbnail.length', 400);

			$image = self::load($image_path)->crop_resize($length, $length);
			$save_path = self::build_real_thumbnail_path($basename);
			$image->save($save_path);

		} catch (\Exception $e) {
			\Log::error($e);
			throw new Libs_Image_Thumbnail_Exception('fail create thumbnail', __LINE__);
		}
	}
}

class Libs_Image_Thumbnail_Exception extends Libs_Exception {}
