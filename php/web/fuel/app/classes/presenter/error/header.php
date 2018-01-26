<?php
class Presenter_Error_Header extends Presenter_Error
{
	public function view()
	{
		parent::view();
		$this->title = Libs_Lang::get('common.title');
	}
}
