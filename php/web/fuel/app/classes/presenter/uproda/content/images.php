<?php
class Presenter_Uproda_Content_Images extends \Presenter
{
	public function view()
	{
		$this->set('page', $this->param['page']);
	}
}
