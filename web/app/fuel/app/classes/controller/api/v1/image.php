<?php
class Controller_Api_V1_Image extends Controller_Api_V1
{
	public function get_list($page)
	{
		try {
			$this->format = 'html';
			$mode = \Libs_Settings::get_listmode()?'image/listview':'image/thumbnailview';
			$view = \Presenter::forge('image/list', 'view', null, $mode)->set('param', ['page' => $page]);

			return $this->response($view->render());
		} catch (\Exception $e) {
			\Log::error($e);
			throw new HttpNotFoundException();
		}
	}

	public function post_add()
	{
		\Log::info('recive post add');
		try {
			\Libs_Deny_Ip::check(\Input::real_ip());
			\Libs_Csrf::check_token();
			\Libs_Captcha::check();
			\Libs_Deny_Ip::enable_post(\Input::real_ip());
			\Libs_Deny_Word::check(\Input::post('comment'));
			\Libs_Captcha::delete_session();

			if (($file = \Libs_Image::upload()) !== null)
			{
				\Libs_Deny_Ip::set_ip(\Input::real_ip());
				//ファイル数がｔぽｋ
				$delete_images = \Libs_Image::get_images_for_delete();
				\Libs_Image::delete_by_images($delete_images);
				return $this->response([
					'status' => 200,
					'image'  => \Uri::create('image/'.\Arr::get($file, 'basename'))
				], 200);
			}
		} catch (\Exception $e) {
			\Log::error($e);
			throw new HttpNoAccessException('Failed Upload Image', 0, $e);
		}
	}
}
