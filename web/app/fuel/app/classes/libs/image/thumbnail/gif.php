<?php

class Libs_Image_Thumbnail_Gif extends Libs_Image_Thumbnail
{
	public function create($file)
  {
		try {
			$basename = \Arr::get($file, 'basename');
			$length = Libs_Config::get('board.thumbnail.length', 400);
			$image_path = self::build_real_image_path($basename, \Arr::get($file, 'ext'));
			$save_path = self::build_real_thumbnail_path($basename, \Arr::get($file, 'ext'));

      //Imagic本体を利用する
      $image = new \Imagick();
      $image->readImage($image_path);
      $image->setFirstIterator();
      $image = $image->coalesceImages();
      do {
          //$image->resizeImage($length, $length, 0, 0.8);
          $image->cropThumbnailImage($length, $length);
      } while ($image->nextImage());
      $image = $image->optimizeImageLayers();

      $image->writeImages($save_path, true);
      $image->clear();
		} catch (\Exception $e) {
			\Log::error($e);
			throw new Libs_Image_Thumbnail_Exception('fail create thumbnail', __LINE__);
		}
  }

}

