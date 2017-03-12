<?php
namespace Nejitsu;
class Presenter_Images_Content extends Presenter_Images
{
	public function view()
	{
		parent::view();
		$per_page = \Libs_Config::get('board.pagination.per_page', 100);
		$offset = (\Arr::get($this->param, 'page', 1) - 1) * $per_page;
		$images = \Libs_Image::get_all_images($offset, $per_page);
		$this->set('images', $images);

		//imageパス作成
		$this->set_safe('build_image_url', function($basename) {
			return \Libs_Image::build_image_url($basename);
	  });

		$this->set_safe('ng2str', function($ng) {
			return $ng==='0'?'glyphicon-thumbs-up':'glyphicon-thumbs-down';
		});

		$this->set_safe('format_bytes', function($bytes) {
			return \Num::format_bytes($bytes);
		});

		$this->set_safe('format_date', function($date) {
			return \Date::forge(strtotime($date))->format('%Y/%m/%d %H:%M');
		});

		$this->set_safe('hash', function($id) {
			return \Libs_Image::hash($id);
		});
	}
}

