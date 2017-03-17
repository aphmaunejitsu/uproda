<?php
namespace Nejitsu;
class Presenter_Hash_Content extends Presenter_Hash
{
	public function view()
	{
		parent::view();

		$hash = $this->param['hash'];
		$per_page = \Libs_Config::get('board.pagination.per_page', 100);
		$offset = (\Arr::get($this->param, 'page', 1) - 1) * $per_page;
		$images = \Libs_Image::get_images_by_image_hash($hash, $per_page, $offset);
		$this->set('images', $images);

		//pager
		$count = count($images);
		$config = [
		    'pagination_url' => 'nejitsu/images',
		    'uri_segment' => 3,
		    'per_page'    => \Libs_Config::get('board.pagination.per_page', 100),
				'num_links'   => 10,
		    'total_items' => $count,
				'name'        => 'bootstrap3',
		];
		$this->set_safe('pagination', \Pagination::forge('bootstrap3', $config));
		$this->set('total', $count);
		$this->set('image', reset($images));

		//imageパス作成
		$this->set_safe('build_image_url', function($basename) {
			return \Libs_Image::build_image_url($basename);
	  });

		$this->set_safe('build_thumbnail_url', function($basename) {
			return \Libs_Image_Thumbnail::build_url($basename);
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

