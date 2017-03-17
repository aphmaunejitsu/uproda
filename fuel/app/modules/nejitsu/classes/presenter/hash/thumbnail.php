<?php
namespace Nejitsu;
class Presenter_Hash_Thumbnail extends Presenter_Hash
{
	public function view()
	{
		parent::view();

		$hash = $this->param['hash'];
		$image = \Libs_Image::get_images_by_image_hash($hash, 1, $offset);
		$this->set('image', reset($image));
		//imageパス作成
		$this->set_safe('build_image_url', function($basename) {
			return \Libs_Image::build_image_url($basename);
	  });

		$this->set_safe('build_thumbnail_url', function($basename) {
			return \Libs_Image_Thumbnail::build_url($basename);
		});

	}
}


