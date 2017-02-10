<?php
class Presenter_Error_Header extends \Presenter
{
	public function view()
	{
		$this->title = Libs_Lang::get('common.title');
	}

	public function view_404()
	{
		$mes = Libs_Lang::get('failedup');
		$mes = Arr::get($mes, rand(0, sizeof($mes) - 1));
		$this->title = Libs_Lang::get('common.title');
		$this->message = $mes;
	}
}
