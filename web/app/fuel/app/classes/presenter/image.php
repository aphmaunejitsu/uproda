<?php
class Presenter_Image extends Presenter_Uproda
{
	protected function build_thumbnail_url($basename, $ext)
	{
		if ($basename === null)
		{
			return \Theme::instanse()->asset->get_file('dummy.jpg', 'img');
		}
		else
		{
			return \Libs_Image_Thumbnail::build_url($basename, $ext);
		}
	}

	//imageパス作成
	protected function build_image_url($basename)
	{
		if ($basename === null)
		{
			return \Theme::instanse()->asset->get_file('dummy.jpg', 'img');
		}
		else
		{
			return \Uri::create('/image/:basename', ['basename' => $basename]);
		}
	}

	protected function build_image_real_url($basename, $ext)
	{
		if ($basename === null or $ext === null)
		{
			return \Theme::instanse()->asset->get_file('dummy.jpg', 'img');
		}
		else
		{
            return \Libs_Image::build_image_real_url($basename, $ext);
		}
	}

	protected function hash($id)
	{
		return \Libs_Image::hash($id);
	}

}
