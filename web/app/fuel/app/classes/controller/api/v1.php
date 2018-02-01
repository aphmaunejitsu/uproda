<?php
class Controller_Api_V1 extends Controller_Api
{
	public function before()
	{
		parent::before();
		\Asset::add_path('assets/global/js/', 'js');
		\Asset::add_path('assets/global/img/', 'img');
		\Asset::add_path('assets/global/css/', 'css');
		\Libs_Config::load();
	}

}
