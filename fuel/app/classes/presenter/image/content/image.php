<?php
class Presenter_Image_Content_Image extends \Presenter
{
	public function view()
	{
		$id = $this->param['id'];
		if (($image = Libs_Image::get($id)) === null)
		{
			throw new \Exception('image not found');
		}

		$this->src = \Str::tr('/:image_dir/:image_short_dir/:basename.:ext', [
				'image_dir'       => Libs_Config::get('board.dir'),
				'image_short_dir' => Libs_Image::get_two_char_from_basename(\Arr::get($image, 'basename')),
				'basename'        => \Arr::get($image, 'basename'),
				'ext'             => \Arr::get($image, 'ext'),
		]);

		$this->message = \Arr::get($image, 'comment', null);
		$this->set('image', $image);
	}
}
