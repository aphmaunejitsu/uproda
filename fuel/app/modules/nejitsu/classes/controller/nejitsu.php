<?php
namespace Nejitsu;
/**
 * 管理サイト基底クラス
 *
 *
 **/
class Controller_Nejitsu extends \Controller_Rest
{
	protected $noauth = [
		'nejitsu/login',
		'nejitsu/auth',
	];

	public function before()
	{
		if ( ! \Auth::check())
		{
			if ( ! in_arrayi(\Request::active()->uri->string(), $this->noauth))
			{
				\Response::redirect('nejitsu/login');
				return;
			}
		}

		\Libs_Config::load();
		$this->theme = \Theme::instance();
		$this->theme->active('nejitsu');

		if ( ! \Input::is_ajax() or ! \Request::is_hmvc())
		{
			$this->theme->asset->add_path('assets/global', ['css', 'js', 'img']);
			$this->theme->set_template('index');
			$this->theme->set_partial('head', 'head')->set('title', 'nejitsu');
			$this->theme->set_partial('header', 'header');
			$this->theme->set_partial('footer', 'footer');
			$this->theme->set_partial('js', 'js');
		}
	}

	public function action_index()
	{
		$this->theme->set_partial('contents', 'contents')->set([
			'content' => $this->theme->presenter('dashboard/content'),
			'sidebar' => $this->theme->presenter('sidebar')->set('param', ['active' => 'dashboard'])
		]);
	}

	public function action_login()
	{
		\Auth::logout();
		$this->theme->set_template('login');
		$this->theme->set_partial('header', 'login/header', true);
		$this->theme->set_partial('contents', 'login/contents', true);

	}

	public function post_auth()
	{
		try {
			if ( ! \Security::check_token())
			{
				throw new \Exception('token error: '.\Input::real_ip());
			}

			$post = [
				'username' => \Security::clean(\Input::post('username'), ['strip_tags', 'htmlentities']),
				'password' => \Security::clean(\Input::post('password'), ['strip_tags', 'htmlentities']),
			];

			$v = \Validation::forge();
			$v->add_field('username', 'username', 'required|valid_email');
			$v->add_field('password', 'password', 'required|valid_string[alpha,numeric,punctuation,dashes,quotes,slashes,brackets,braces]');
			if ( ! $v->run($post))
			{
				throw new \Exception('validate error');
			}

			$auth = \Auth::instance();
			if ( ! $auth->login($post['username'], $post['password']))
			{
				throw new \Exception('auth error');
			}

			\Response::redirect('nejitsu');
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			//何らかのエラーは全てログイン画面へリダイレクト
			\Response::redirect('nejitsu/login');
		}
	}

	public function post_delete()
	{

	}

	public function action_images($page = 1)
	{
		$this->theme->set_partial('contents', 'contents')->set([
			'content' => $this->theme->presenter('images/content')->set('param', ['page' => $page]),
			'sidebar' => $this->theme->presenter('sidebar')->set('param', ['active' => 'images']),
		]);

	}

	public function after($response)
	{
		if (empty($response) or ! $response instanceof Response)
		{
			$response = \Response::forge($this->theme->render());
		}

		$response->set_status($this->response_status);
		return parent::after($response);
	}
}
