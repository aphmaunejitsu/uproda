<?php
class Presenter_Image_List extends \Presenter
{
	public function view()
	{
		$per_page = \Libs_Config::get('board.pagenation.per_page', 100);
		$offset = (\Arr::get($this->param, 'page', 1) - 1) * $per_page;
		$this->set('images', Libs_Image::get_images($offset, $per_page));
		$this->set('image_dir', Libs_Config::get('board.dir'));
		$this->set('thumbnail_dir', Libs_Config::get('board.thumbnail.dir'));
		$this->set('width', Libs_Config::get('board.thumbnail.width'));
		$this->set('height', Libs_Config::get('board.thumbnail.height'));

		//thumbnailパス作成
		$this->set_safe('build_thumbnail_url', function($image_dir, $thumbnail_dir, $basename) {
			return \Str::tr('/:image_dir/:image_short_dir/:thumbnail_dir/:basename.jpg', [
				'image_dir'       => $image_dir,
				'image_short_dir' => Libs_Image::get_two_char_from_basename($basename),
				'thumbnail_dir'   => $thumbnail_dir,
				'basename'        => $basename,
			]);
		});

		//imageパス作成
		$this->set_safe('build_image_url', function($basename) {
			return \Str::tr('/image/:basename', ['basename' => $basename]);
	   	});

		//image実体パス作成
		$this->set_safe('build_real_image_url', function($image_dir, $basename, $ext) {
			return \Str::tr('/:image_dir/:image_short_dir/:basename.:ext', [
				'image_dir'       => $image_dir,
				'image_short_dir' => Libs_Image::get_two_char_from_basename($basename),
				'basename'        => $basename,
				'ext'             => $ext,
			]);
		});
	}
}
