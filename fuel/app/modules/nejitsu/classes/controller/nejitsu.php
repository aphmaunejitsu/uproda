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
		'nejitsu/logout',
		'nejiteu/auth',
	];
	public function before()
	{
		$this->theme = \Theme::instance();
		$this->theme->active('nejitsu');
		if ( ! \Auth::check())
		{
			if (\Request::active()->uri->string() !== 'nejitsu/login')
			{
				\Response::redirect('nejitsu/login');
			}
		}

		$this->theme->asset->add_path('assets/global', ['css', 'js', 'img']);
		if ( ! \Input::is_ajax())
		{
			$template = $this->theme->set_template('index');
			$this->theme->set_partial('head', 'head')->set('title', 'nejitsu');
			$this->theme->set_partial('header', 'header');
			$this->theme->set_partial('footer', 'footer');
			$this->theme->set_partial('js', 'js');
		}
	}

	public function action_index()
	{
	}

	public function action_login()
	{
		$template = $this->theme->set_template('login');
		$this->theme->set_partial('header', 'login/header', true);
		$this->theme->set_partial('contents', 'login/contents', true);
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

