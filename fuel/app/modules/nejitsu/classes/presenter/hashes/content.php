<?php
namespace Nejitsu;
class Presenter_Hashes_Content extends Presenter_Nejitsu
{
	public function view()
	{
		parent::view();
		$per_page = \Libs_Config::get('board.pagination.per_page', 100);
		$offset = (\Arr::get($this->param, 'page', 1) - 1) * $per_page;
		$hash = \Libs_Image_Hash::get_all($per_page, $offset);
		$this->set('hashes', $hash);

		//pager
		$count = \Model_Image_Hash::count();
		$config = [
		    'pagination_url' => 'nejitsu/hashes',
		    'uri_segment' => 3,
		    'per_page'    => \Libs_Config::get('board.pagination.per_page', 100),
				'num_links'   => 10,
		    'total_items' => $count,
				'name'        => 'bootstrap3',
		];
		$this->set_safe('pagination', \Pagination::forge('bootstrap3', $config));
		$this->set('total', $count);

		$this->set_safe('ng2str', function($ng) {
			return $ng==='0'?'glyphicon-thumbs-up':'glyphicon-thumbs-down';
		});

		$this->set_safe('id2hash', function($id) {
			return \Libs_Image::hash($id);
		});
	}
}


