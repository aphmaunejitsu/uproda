<?php
class Libs_Image_Hash
{
	public static function create($basename, $ext)
	{
		return self::create_by_file(Libs_Image::build_real_image_path($basename, $extension));
	}

	public static function create_by_file($path)
	{
		return hash_file('sha256', $path);
	}

	public static function get($basename, $ext)
	{
		return self::get_by_hash(self::create($basename, $ext));
	}

	public static function get_by_hash($hash)
	{
		try {
			return Model_Image_Hash::find_one_by_hash($hash);
		} catch (\Exception $e) {
			\Log::error($e);
			return null;
		}
	}

	public static function save($basename, $ext)
	{
		return self::save_by_hash(self::create($basename, $ext));
	}

	public static function save_by_hash($hash)
	{
		try {
			if (($result = Model_Image_Hash::forge()->set(['hash' => $hash, 'ng' => 0, 'comment' => null])->save()))
			{
				return reset($result);
			}

			return null;
		} catch (\Exception $e) {
			\Log::error($e);
			throw new Libs_Image_Hash_Exception('fail create Hash', __LINE__);
		}
	}
}

class Libs_Image_Hash_Exception extends Libs_Exception {}

