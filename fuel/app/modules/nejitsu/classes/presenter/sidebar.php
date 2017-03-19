<?php
namespace Nejitsu;
class Presenter_Sidebar extends Presenter_Nejitsu
{
	//Sidebarのアクティブ
	private $active = [
		'dashboard' => 1,
		'images'    => 0,
		'hashes'    => 0,
	];

	public function view()
	{
		parent::view();
		if (\Arr::search($this->active, $this->param['active'], null) !== null)
		{
			$this->active['dashboard'] = 0;
			$this->active[$this->param['active']] = 1;
		}

		$this->set_safe('is_active', function($active) {
			if (\Arr::get($this->active, $active, 0) === 1)
			{
				return 'current';
			}
			else
			{
				return 'no';
			}
		});
	}
}
