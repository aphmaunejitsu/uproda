<?php
class Presenter_Image_Content_Image extends \Presenter
{
	public function view()
	{
		$this->src = $this->param['src'];
	}
}
