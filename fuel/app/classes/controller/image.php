<?php
class Controller_Image extends Controller_Uproda
{
	public function before()
	{
		parent::before();
	}

	public function action_index()
	{
		try {

			if (($path = Libs_image::search($this->param('image'))) === null)
			{
				throw new \Exception('image not found.');
			}

			$this->theme->set_partial('header', $this->theme->presenter('image/header'));
			$this->theme->set_partial('content', $this->theme->presenter('image/content')->set('param' ,['src' => Uri::base(false).$path]));
		} catch ( \Exception $e ) {
			\Log::error(__FILE__.':'.$e->getMessage());
			throw new HttpNotFoundException();
		}
	}

	public function get_list()
	{
		try {
			if (\Input::method() !== 'GET')
			{
				throw new \Exception('invalid access [bad method]: '.\Input::real_ip());
			}

			$this->deafult_format = 'html';
			//バリデーションを行う
			$page = $this->param('page');
			if (is_numeric($page))
			{
			}
			$view = $this->theme->presenter('image/list')->set('param', ['page' => $this->param('page')]);

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

	/**
	 *
	 **/
	public function post_upload()
	{
		try {
			if (\Input::method() !== 'POST')
			{
				throw new \Exception('invalid access [bad method]: '.\Input::real_ip());
			}

			//check token
			if ( ! \Security::check_token())
			{
				throw new \Exception('invalid access [token error]: '.\Input::real_ip());
			}

			if (($file = Libs_Image::upload()) !== null)
			{
				//サムネイル作成
				Libs_Image::thumbnail($file);


				//アップロード成功
				$name = explode('.', $file['saved_as']);
				//画像ビューへリダイレクト
				\Response::redirect('image/'.$name[0]);
				return;
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
}
