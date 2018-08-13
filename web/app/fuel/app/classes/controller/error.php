<?php
class Controller_Error extends \Controller_Rest
{
	protected $default_format = 'json';
	protected $ignore_http_accept = true;
	protected $no_data_status = 404;
	protected $no_method_status = 404;
	protected $_supported_formats = [
		'html' => 'text/html',
		'json' => 'application/json'
	];

	public function before()
	{
		parent::before();
		\Libs_Config::load();
		\Libs_Lang::load();
		\Libs_Lang::load('error');

		if (! \Input::is_ajax())
		{
			$this->theme = \Theme::instance();
			$this->theme->active('skeleton');
			$this->theme->asset->add_path('assets/global', ['css', 'js', 'img']);
			$template = $this->theme->set_template('template');
			$this->theme->set_partial('head', $this->theme->presenter('head'), 'view', true);
			$this->theme->set_partial('header', $this->theme->presenter('error/header'), 'view', true);
			$this->theme->set_partial('footer', $this->theme->presenter('footer'), 'view', true );
			$this->theme->set_partial('form', null);
		}
	}

	public function action_index($page = 1)
	{
		throw new HttpNotFoundException();
	}

	public function action_400()
	{
		$this->response_status = 400;
		if (\Input::is_ajax())
		{
			return $this->response([
				'error'  => 'Bad Request',
				'status' => 400,
			], 400);
		}
		else
		{
			$this->theme->set_partial('content', 'error/content')->set([
				'error'  => $this->theme->presenter('error/400'),
			]);
		}
	}

	public function action_403()
	{
		$this->response_status = 403;
		if (\Input::is_ajax())
		{
			$this->default_format = 'json';
			$ex = \Arr::get(\Request::active()->method_params, '1.0');
			$message = 'エラーが発生しました';
			if ($ex !== null)
			{
				if (($exp = $ex->getPrevious()) !== null)
				{
					$class = get_class($exp);
					$message = \Libs_Lang::get($class.'.'.$exp->getCode());
				}
			}

			return $this->response([
				'error'   => 'Access Forbidden',
				'message' => $message,
				'status'  => 403,
			], 403);
		}
		else
		{
			$this->theme->set_partial('content', 'error/content')->set([
				'error'  => $this->theme->presenter('error/403'),
			]);
		}
	}

	public function action_404()
	{
		$this->response_status = 404;
		if (\Input::is_ajax())
		{
			return $this->response([
				'error'  => 'Page Not Found',
				'status' => 404,
			], 404);
		}
		else
		{
			$this->theme->set_partial('content', 'error/content')->set([
				'error'  => $this->theme->presenter('error/404'),
			]);
		}
	}

	public function action_500()
	{
		$this->response_status = 500;
		if (\Input::is_ajax())
		{
			return $this->response([
				'error'  => 'Internal Server Error',
				'status' => 500,
			], 500);
		}
		else
		{
			$this->theme->set_partial('content', 'error/content')->set([
				'error'  => $this->theme->presenter('error/500'),
			]);
		}
	}

	public function after($response)
	{
		if (\Input::is_ajax())
		{
			if (is_array($response))
			{
			    $response = $this->response($response);
			}

			if ( ! $response instanceof Response)
			{
			    $response = $this->response;
			}
		}
		else
		{
			if (empty($response) or ! $response instanceof Response)
			{
				$response = \Response::forge($this->theme->render());
			}

			$response->set_status($this->response_status);
		}
		return parent::after($response);
	}
}
