<?php
class Controller_Api_V1_Image extends Controller_Api_V1
{
	public function get_list($page)
	{
		try {
			$mode = \Libs_Settings::get_listmode()?'image/listview':'image/thumbnailview';
			$view = \Presenter::forge('image/list', 'view', null, $mode)->set('param', ['page' => $page]);

			return $this->response($view->render());
		} catch (\Exception $e) {
			\Log::error($e);
			throw new HttpNotFoundException();
		}
	}
}
