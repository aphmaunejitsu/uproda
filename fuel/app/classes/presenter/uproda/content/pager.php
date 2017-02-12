<?php
class Presenter_Uproda_Content_Pager extends \Presenter
{
	public function view()
	{
		$count = Libs_Image::count_all();
		//ページネーションの設定用変数を作成します。
		$config = array(
		        'pagination_url' => '/',
		        'uri_segment' => 1,
		        'per_page' => Libs_Config::get('board.pagination.per_page', 100),
		        'total_items' => $count,
		);

		$this->set_safe('pagination', \Pagination::forge('uprodapage', $config));
		$this->set('page', $this->param['page']);
	}
}

