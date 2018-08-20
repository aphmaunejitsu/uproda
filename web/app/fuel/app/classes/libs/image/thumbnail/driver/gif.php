<?php

class Libs_Image_Thumbnail_Driver_Gif extends Libs_Image_Thumbnail
{
  protected $ext = 'gif';
	public function create($file)
  {
		try {
      list($basename, $ext, $image_path, $thumbnail_dir, $image_dir, $save_path, $length) = self::path_infos($file);

      //Imagic本体を利用する
      $image = new \Imagick();
      $image->readImage($image_path);
      $image->setFirstIterator();
      $image = $image->coalesceImages();
      do
      {
          $image->cropThumbnailImage($length, $length);
      } while ($image->nextImage());

      $image = $image->optimizeImageLayers();

      $image->writeImages($save_path, true);
      $image->clear();
      return [$basename, $ext];
		} catch (\Exception $e) {
			\Log::error($e);
			throw new Libs_Image_Thumbnail_Exception('fail create thumbnail', __LINE__);
		}
  }
}

