<?php
class Presenter_Header extends \Presenter
{
	public function view()
	{
		$this->title = Libs_Lang::get('common.title');
		$this->upload = Libs_Lang::get('common.menu.upload');
	}
}
