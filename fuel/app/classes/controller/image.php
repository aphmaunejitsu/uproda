<?php
class Controller_Image extends Controller_Uproda
{

	public function before()
	{
		parent::before();
	}

	public function action_index($page = null)
	{
		try {
			\Libs_Image::check_id($page);

			$image = \Libs_Image::get($page);

			$this->theme->asset->js(['clipboard.min.js', 'cp.js'], [], 'clipboard', false);

			$this->theme->set_partial('content', 'image/content')->set([
				'image' =>  $this->theme->presenter('image/content/image')->set('param', ['id' => $page, 'image' => $image])
			]);
		} catch ( \Exception $e ) {
			\Log::error($e);
			throw new HttpNotFoundException();
		}
	}
}
