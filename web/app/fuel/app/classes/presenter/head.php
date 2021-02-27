<?php
class Presenter_Head extends Presenter_Uproda
{
	public function view()
	{
		parent::view();

        $title = Libs_Lang::get('common.title');

        if (isset($this->param['image']))
        {
            $this->title = $title.' | '.$this->param['image']->basename;
        } else {
		    $this->title = $title;
        }

		$this->description = Libs_Lang::get('common.description');
	}
}
