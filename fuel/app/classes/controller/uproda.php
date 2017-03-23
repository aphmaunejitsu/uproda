<?php
class Controller_Uproda extends Controller
{
	protected $default_format = 'json';
	protected $ignore_http_accept = true;

	public function before()
	{
		parent::before();

		Libs_Config::load();
		Libs_Lang::load();

		$this->theme = \Theme::instance();
		$this->theme->active('skeleton');
		$this->theme->asset->add_path('assets/global', ['css', 'js', 'img']);

		if ( ! \Input::is_ajax())
		{
			$template = $this->theme->set_template('template');
			$this->theme->set_partial('head', $this->theme->presenter('head'));
			$this->theme->set_partial('header', $this->theme->presenter('header'));
			$this->theme->set_partial('footer', $this->theme->presenter('footer'));
			$this->theme->set_partial('form', $this->theme->presenter('form'));
		}
	}

	public function action_index($page = 1)
	{
		try {
			$this->theme->asset->js(['jquery.lazyload.min.js','list.image.js'], [], 'jquery-list-loading', false);
			Libs_Csrf::flash();

			//画像取得
			$this->theme->set_partial('content', 'uproda/content')->set([
				'pager'  => $this->theme->presenter('uproda/content/pager')->set('param', ['page' => $page]),
				'images' => $this->theme->presenter('uproda/content/images')->set('param', ['page' => $page]),
			]);
		} catch (\Exception $e) {
			\Log::error($e->getMessage());
			throw new HttpNotFoundException();
		}
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
