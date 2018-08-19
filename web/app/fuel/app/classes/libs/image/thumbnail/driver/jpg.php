<?php

class Libs_Image_Thumbnail_Driver_Jpg extends Libs_Image_Thumbnail
{
  protected $ext = 'jpg';
	public function create($file)
	{
		try {
      list($basename, $ext, $image_path, $thumbnail_dir, $image_dir, $save_path, $length) = self::path_infos($file);

			$image = \Image::load($image_path)->crop_resize($length, $length);

			$image->save($save_path);
		} catch (\Exception $e) {
			\Log::error($e);
			throw new Libs_Image_Thumbnail_Exception('fail create thumbnail', __LINE__);
		}
	}

  public function get_ext()
  {
    return $this->ext;
  }
}
