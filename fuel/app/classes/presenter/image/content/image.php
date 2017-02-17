<?php
class Presenter_Image_Content_Image extends Presenter_Image_Content
{
	public function view()
	{
		parent::view();
		$id = $this->param['id'];
		if (($image = Libs_Image::get($id)) === null)
		{
			throw new \Exception('image not found');
		}

		$this->src = $this->build_image_real_url(\Arr::get($image, 'basename', null), \Arr::get($image, 'ext', null));
		$this->message = \Arr::get($image, 'comment', null);
		$this->set('image', $image);
	}
}
