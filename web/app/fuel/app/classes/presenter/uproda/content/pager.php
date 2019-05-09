<?php
class Presenter_Uproda_Content_Pager extends \Presenter
{
	public function view()
	{
		$count = Libs_Image::count();
		$config = [
		    'pagination_url' => '/',
		    'uri_segment' => 1,
		    'per_page'    => Libs_Config::get('board.pagination.per_page', 100),
				'num_links'   => 5,
		    'total_items' => $count,
			//'show_first'  => true,
			//'show_last'   => true
		];

		$this->set_safe('pagination', \Pagination::forge('uprodapage', $config));
		$this->set('page', $this->param['page']);
	}
}

