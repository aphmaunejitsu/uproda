<?php
class Controller_Error extends Controller
{
	public function before()
	{
		parent::before();

		Libs_Config::load();
		Libs_Lang::load();

		$this->theme = \Theme::instance();
		$this->theme->active('skeleton');
	}

	public function action_index($page = 1)
	{
		throw new HttpNotFoundException();
	}

	public function action_404()
	{
		$this->theme->set_partial('content', 'error/content')->set([
			'error'  => $this->theme->presenter('error/404'),
		]);
		$this->response_status = 404;
	}

	public function action_500()
	{
		$this->theme->set_partial('content', 'error/content')->set([
			'error'  => $this->theme->presenter('error/500'),
		]);
		$this->response_status = 500;
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
