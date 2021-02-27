<?php
class Controller_Image extends Controller_Uproda
{

	public function before()
	{
		Libs_Config::load();
		Libs_Lang::load();

		$this->theme = \Theme::instance();
		$this->theme->active('skeleton');
		$this->theme->asset->add_path('assets/global', ['css', 'js', 'img']);

		if ( ! \Input::is_ajax())
		{
			$template = $this->theme->set_template('template');
			$this->theme->set_partial('header', $this->theme->presenter('header'));
			$this->theme->set_partial('footer', $this->theme->presenter('footer'));
			$this->theme->set_partial('form', $this->theme->presenter('form'));
		}
	}

	public function action_index($page = null)
	{
		try {
			\Libs_Image::check_id($page);

			$image = \Libs_Image::get($page);

			$this->theme->asset->js(['clipboard.min.js', 'cp.js'], [], 'clipboard', false);

			$this->theme->set_partial('head', $this->theme->presenter('head')->set('param', ['image' => $image]));
			$this->theme->set_partial('content', 'image/content')->set([
				'image' =>  $this->theme->presenter('image/content/image')->set('param', ['id' => $page, 'image' => $image])
			]);
		} catch ( \Exception $e ) {
			\Log::error($e);
			throw new HttpNotFoundException();
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

			\Libs_Image::delete_by_hash($hash, \Input::post('pass'));
			//失敗は無視してトップへリダイレクト
			\Response::redirect('/');
		} catch (\Exception $e) {
			\Log::error($e);
			throw new HttpNoAccessException();
		}
	}
}
