<?php
class Presenter_Header extends Presenter_Uproda
{
	public function view()
	{
		parent::view();
		$this->title = Libs_Lang::get('common.title');
		$this->upload = Libs_Lang::get('common.menu.upload');
	}
}
