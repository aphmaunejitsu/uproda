<?php
class Presenter_Image_Content_Image extends Presenter_Image_Content
{
	public function view()
	{
		parent::view();
		$id = $this->param['id'];
		$image = $this->param['image'];

		$basename = \Arr::get($image, 'basename', null);
		$ext = \Arr::get($image, 'ext', null);

		if (Libs_Image::exists($basename, $ext))
		{
			$src = $this->build_image_real_url($basename, $ext);
		}
		else
		{
			$src = \Theme::instance()->asset->get_file('404.jpg', 'img');
		}

		$this->src = $src;
		$this->message = \Arr::get($image, 'comment', null);
		$this->set('image', $image);

		$this->set_safe('hash', function($id) {
			return $this->hash($id);
		});
	}
}
