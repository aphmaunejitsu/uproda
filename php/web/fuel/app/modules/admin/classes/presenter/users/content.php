<?php
namespace Admin;
class Presenter_Users_Content extends Presenter_Admin
{
	public function view()
	{
		parent::view();
		$per_page = \Libs_Config::get('board.pagination.per_page', 100);
		$offset = (\Arr::get($this->param, 'page', 1) - 1) * $per_page;
		$users = \Model_User::find_all($per_page, $offset);

		$this->set('users', $users);

		//pager
		$count = \Model_User::count();
		$config = [
		    'pagination_url' => 'admin/users',
		    'uri_segment' => 3,
		    'per_page'    => \Libs_Config::get('board.pagination.per_page', 100),
				'num_links'   => 10,
		    'total_items' => $count,
				'name'        => 'bootstrap3',
		];
		$this->set_safe('pagination', \Pagination::forge('bootstrap3', $config));
		$this->set('total', $count);

		$this->set_safe('format_date', function($date) {
			return \Date::forge($date)->format('%Y/%m/%d %H:%M');
		});

		$this->set_safe('id2hash', function($id) {
			return \Libs_Hash::crypt($id);
		});
	}
}


