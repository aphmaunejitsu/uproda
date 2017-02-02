<?php
class Controller_Uproda extends Controller
{
	public function before()
	{
		parent::before();

		Libs_Config::load();
		Libs_Lang::load();
		$this->theme = \Theme::instance();
		$this->theme->active('skeleton');
		$template = $this->theme->set_template('template');
		$this->theme->set_partial('head', 'head');
	}

	public function action_index()
	{
		$this->theme->set_partial('header', 'uproda/header')->set([
			'header_message' => Libs_Lang::get('common.header_message'),
		]);
		$form = $this->theme->view('uproda/content/form');
		$pager = $this->theme->view('uproda/content/pager');
		$images = $this->theme->view('uproda/content/images')->set(['images' => []]);
		$this->theme->set_partial('content', 'uproda/content')->set([
			'form'   => $form,
			'pager'  => $pager,
			'images' => $images,
		]);
	}

	public function action_404()
	{
		$mes = Libs_Lang::get('neta');
		$mes = Arr::get($mes, rand(0, sizeof($mes) - 1));
		$this->theme->set_partial('header', 'uproda/header')->set([
			'header_message' => $mes,
		]);
		$this->theme->set_partial('content', 'uproda/404');
		$this->response_status = 404;
	}


	public function after($response)
	{
			if (empty($response) or ! $response instanceof Response) {
					$response = \Response::forge($this->theme->render());
			}

			$response->set_status($this->response_status);

			return parent::after($response);
	}

}
