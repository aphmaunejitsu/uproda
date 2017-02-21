<?php
class Presenter_Settings_Content_Index extends Presenter_Settings_Content
{
	public function view()
	{
		$listmode = Libs_Image::get_listmode();
		$this->listmode_label = Libs_Lang::get('settings.listmode');
		$this->listmode = $listmode ? '' : 'checked';
		parent::view();
	}
}
