<?php
class Controller_Image extends Controller_Uproda
{
	public function before()
	{
		parent::before();
	}

	public function action_index($page = null)
	{
		try {
			Libs_Image::check_id($page);

			$image = Libs_Image::get($page);

			$this->theme->asset->js(['clipboard.min.js', 'cp.js'], [], 'clipboard', false);

			$this->theme->set_partial('content', 'image/content')->set([
				'image' =>  $this->theme->presenter('image/content/image')->set('param', ['id' => $page, 'image' => $image])
			]);
		} catch ( \Exception $e ) {
			\Log::error($e->getMessage());
			throw new HttpNotFoundException();
		}
	}

	public function get_list($page)
	{
		try {
			$mode = \Libs_Settings::get_listmode()?'image/listview':'image/thumbnailview';
			$view = $this->theme->presenter('image/list', 'view', null, $mode)->set('param', ['page' => $page]);

			return $this->response($view->render());
		} catch (\Exception $e) {
			\Log::error($e);
			throw new HttpNotFoundException();
		}
	}

	public function post_upload()
	{
		try {
			Libs_Deny_Ip::check(\Input::real_ip());
			Libs_Csrf::check_token();
			Libs_Captcha::check();
			Libs_Deny_Ip::enable_post();

			$v = \Validation::forge();
			$v->add_callable('Libs_Deny_Word');
			$v->add('words', 'deny words')->add_rule('not_contain');
			if ( ! $v->run(['words' => \Input::post('comment')], true))
			{
				throw new \Exception('invalid access [Ng Word]: '.\Input::post('comment'));
			}

			if (($file = Libs_Image::upload()) !== null)
			{
				//サムネイル作成
				Libs_Image::thumbnail($file);

				//ファイル数がｔぽｋ
				$delete_images = Libs_Image::get_images_for_delete();
				Libs_Image::delete_by_images($delete_images);
				return $this->response([
					'status' => 200,
					'image'  => \Uri::create('image/'.\Arr::get($file, 'basename'))
				], 200);
			}
		} catch (\Exception $e) {
			\Log::error($e);
			throw new HttpNoAccessException();
		}
	}

	public function post_delete()
	{
		try {
			Libs_Deny_Ip::check(\Input::real_ip());
			Libs_Csrf::check_token();

			$hash = \Security::clean(\Input::post('file'), ['strip_tags', 'htmlentities']);
			$v = \Validation::forge();
			$v->add_field('hash', 'hash', 'required|valid_string[alpha,numeric]');
			if ( ! $v->run(['hash' => $hash], true))
			{
				throw new \Exception('validate error: '.$hash);
			}

			Libs_Image::delete_by_hash($hash, \Input::post('pass'));
			//失敗は無視してトップへリダイレクト
			\Response::redirect('/');
		} catch (\Exception $e) {
			\Log::error($e);
			throw new HttpNoAccessException();
		}
	}
}
