<?php
namespace Nejitsu;
class Presenter_Hash_Thumbnail extends Presenter_Hash
{
	public function view()
	{
		parent::view();

		$hash = $this->param['hash'];
		$image = \Libs_Image_Hash::get_with_image_by_hash($hash, 1, 0);
		$this->set('image', $image);

		$this->set_safe('build_thumbnail_url', function($basename) {
			return \Libs_Image_Thumbnail::build_url($basename);
		});

		$this->set_safe('write_ng_state', function($ng) {
			return ($ng)?'checked="checked"':'';
		});
	}
}


