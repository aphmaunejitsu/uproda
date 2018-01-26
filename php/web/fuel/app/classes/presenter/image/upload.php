<?php
class Presenter_Image_Upload extends Presenter_Image
{
	public function view()
	{
		parent::view();
		$mes = Libs_Lang::get('failedup');
		$mes = Arr::get($mes, rand(0, sizeof($mes) - 1));
		$this->message = $mes;
	}
}
