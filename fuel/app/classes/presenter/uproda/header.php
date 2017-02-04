<?php
class Presenter_Uproda_Header extends \Presenter
{
	public function view()
	{
		$this->title = Libs_Lang::get('common.title');
		if (isset($this->param))
		{
			$this->header_message = \Arr::get($this->param, 'message', null);
		}
		else
		{
			$this->header_message = Libs_Lang::get('common.header_message');
		}
	}
}
