<?php
class Presenter_Error_404 extends \Presenter
{
	public function view()
	{
		$mes = Libs_Lang::get('404');
		$mes = \Arr::get($mes, rand(0, sizeof($mes) - 1));
		$this->title = Libs_Lang::get('common.title');
		$this->message = $mes;
	}
}
