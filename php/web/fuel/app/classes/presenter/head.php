<?php
class Presenter_Head extends Presenter_Uproda
{
	public function view()
	{
		parent::view();
		$this->title = Libs_Lang::get('common.title');
		$this->description = Libs_Lang::get('common.description');
	}
}
