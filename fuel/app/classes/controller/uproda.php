<?php
class Controller_Uproda extends Controller_Rest
{
	public function before()
	{
		parent::before();

		Libs_Config::load();
		Libs_Lang::load();

		if ( ! \Input::is_ajax())
		{
			$this->theme = \Theme::instance();
			$this->theme->active('skeleton');
			$template = $this->theme->set_template('template');
			$this->theme->set_partial('head', $this->theme->presenter('head'));
		}
	}

	public function action_index()
	{
		try {
			$this->theme->set_partial('header', $this->theme->presenter('uproda/header'));

			//画像取得
			$pager = $this->theme->view('uproda/content/pager');
			$this->theme->set_partial('content', 'uproda/content')->set([
				'form'   => $this->theme->presenter('uproda/content/form'),
				'pager'  => $pager,
				'images' => $this->theme->presenter('uproda/content/images'), //初期表示のみ直接表示
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
