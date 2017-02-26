<?php
class Controller_Settings extends Controller_Uproda
{
	public function before()
	{
		parent::before();
	}

	public function action_index($page = 1)
	{
		try {

			$this->theme->asset->js(['jquery.cookie.js','settings.js'], [], 'settings', false);
			$this->theme->set_partial('content', 'settings/content')->set([
				'index'  => $this->theme->presenter('settings/content/index'),
				'thanks' => $this->theme->presenter('settings/content/thanks')
			]);

		} catch (\Exception $e) {
			\Log::error($e);
			throw new HttpNotFoundException();
		}
	}

}
