<?php
class Presenter_Uproda_Content_Form extends \Presenter
{
	public function view()
	{
		//$this->filelabel = 'AA';
		$this->filelabel = Libs_Lang::get('imgform.filelabel', [
			'size'  => Libs_Config::get('board.maxsize') * 1024,
			'count' => Libs_Config::get('board.maxfiles')
		]);
		$this->dellabel     = Libs_Lang::get('imgform.dellabel');
		$this->commentlabel = Libs_Lang::get('imgform.commentlabel');
		$this->buttonlabel  = Libs_Lang::get('imgform.buttonlabel');
	}
}
