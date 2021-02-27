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
            $this->image = $this->param['image'];
        } else {
		    $this->title = $title;
            $this->image = null;
        }

		$this->description = Libs_Lang::get('common.description');
	}
}
