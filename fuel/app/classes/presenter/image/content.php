<?php
class Presenter_Image_Content extends \Presenter
{
	public function view()
	{
		$this->src = $this->param['src'];
	}
}

