<?php
class Presenter_Head extends \Presenter
{
	public function view()
	{
		$this->title = Libs_Lang::get('common.title');
		$this->description = Libs_Lang::get('common.description');
	}
}
