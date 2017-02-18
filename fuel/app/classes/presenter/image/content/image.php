<?php
class Presenter_Image_Content_Image extends Presenter_Image_Content
{
	public function view()
	{
		parent::view();
		$id = $this->param['id'];
		$image = $this->param['image'];

		$this->src = $this->build_image_real_url(\Arr::get($image, 'basename', null), \Arr::get($image, 'ext', null));
		$this->message = \Arr::get($image, 'comment', null);
		$this->set('image', $image);

		$this->set_safe('hash', function($id) {
			return $this->hash($id);
		});
	}
}
