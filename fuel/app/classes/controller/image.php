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
			\Log::error(__FILE__.':'.$e->getMessage());
			throw new HttpNotFoundException();
		}
	}

	public function get_list($page)
	{
		try {
			if (! is_numeric($page))
			{
				throw new \Exception('invalid access [bad page]: '.\Input::real_ip());
			}

			$mode = \Libs_Settings::get_listmode()?'image/listview':'image/thumbnailview';
			$view = $this->theme->presenter('image/list', 'view', null, $mode)->set('param', ['page' => $page]);

			return $this->response($view->render());
		} catch (\HttpServerErrorException $e) {
			\Log::error(__FILE__.':'.$e->getMessage());
			//もう一回
			throw new HttpServerErrorException();
		} catch (\Exception $e) {
			\Log::error(__FILE__.':'.$e->getMessage());
			throw new HttpNotFoundException();
		}
	}

	public function post_upload()
	{
		try {
			$this->default_format = 'json';
			if (Libs_Deny_Ip::check(\Input::real_ip()))
			{
				throw new \Exception('invalid access [deny access]: '.\Input::real_ip());
			}

			if ( ! Libs_Csrf::check_token())
			{
				throw new \Exception('invalid access [token error]: '.\Input::real_ip());
			}

			if ( ! Captcha::forge('simplecaptcha')->check())
			{
				throw new \Exception('invalid access [captcha error]: '.\Input::real_ip());
			}

			if ( ! Libs_Deny_Ip::enable_post())
			{
				throw new \Exception('invalid access [連投規制]: '.\Input::real_ip());
			}

			if ((\Input::method() !== 'POST') or ( ! \Input::is_ajax()))
			{
				throw new \Exception('invalid access [bad method]: '.\Input::real_ip());
			}

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
			else
			{
				//気持ち悪いけど・・・
				throw new HttpServerErrorException('failed up image');
			}
		} catch (\HttpServerErrorException $e) {
			\Log::error(__FILE__.':'.$e->getMessage());
			//もう一回
			throw new HttpServerErrorException();
		} catch (\Exception $e) {
			\Log::error(__FILE__.':'.$e->getMessage());
			throw new HttpNotFoundException();
		}
	}

	public function post_delete()
	{
		try {
			if (Libs_Deny_Ip::check(\Input::real_ip()) )
			{
				throw new \Exception('invalid access [deny access]: '.\Input::real_ip());
			}

			//check token
			if ( ! Libs_Csrf::check_token())
			{
				throw new \Exception('invalid access [token error]: '.\Input::real_ip());
			}

			if (\Input::method() !== 'POST')
			{
				throw new \Exception('invalid access [bad method]: '.\Input::real_ip());
			}

			$h = \Security::clean(\Input::post('file'), ['strip_tags', 'htmlentities']);
			$v = \Validation::forge();
			$v->add_field('hash', 'hash', 'required|valid_string[alpha,numeric]');
			if ( ! $v->run(['hash' => $h], true))
			{
				throw new \Exception('validate error: '.$h);
			}

			if ( Libs_Image::delete_by_hash($hash, \Input::post('pass')))
			{
				//トップへリダイレクト
				\Response::redirect('/');
				return;
			}

			//気持ち悪いけど・・・
			throw new HttpServerErrorException('failed delete image');
		} catch (\HttpServerErrorException $e) {
			\Log::error(__FILE__.':'.$e->getMessage());
			//もう一回
			throw new HttpServerErrorException();
		} catch (\Exception $e) {
			\Log::error(__FILE__.':'.$e->getMessage());
			throw new HttpNotFoundException();
		}
	}
}
