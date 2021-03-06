<?php
namespace Admin;
class Controller_Hash extends Controller_Admin
{
	protected $default_format = 'json';
	protected $ignore_http_accept = true;

	public function before()
	{
		parent::before();
	}

	public function action_index()
	{
		throw new \HttpNotFoundException();
	}

	public function action_detail($hash, $page = 1)
	{
		if (empty($hash))
		{
			throw new \HttpNotFoundException();
		}

		$hash = \Security::clean($hash, ['strip_tags', 'htmlentities']);
		$v = \Validation::forge();
		$v->add_field('hash', 'hash', 'required|exact_length[26]|valid_string[alpha,numeric]');
		if ( ! $v->run(['hash' => $hash], true))
		{
			throw new \HttpNotFoundException();
		}

		$this->theme->set_partial('contents', 'hash')->set([
			'content'   => $this->theme->presenter('hash/content')->set('param', ['hash' => $hash, 'page' => $page]),
			'thumbnail' => $this->theme->presenter('hash/thumbnail')->set('param', ['hash' => $hash]),
			'sidebar'   => $this->theme->presenter('sidebar')->set('param', ['active' => 'hashes']),
		]);
	}

	public function get_save()
	{
		throw new \HttpNotFoundException();
	}

	public function post_save()
	{
		try {
			\Libs_Csrf::check_token();
			$hash = \Security::clean(\Input::post('file'), ['strip_tags', 'htmlentities']);
			$ng   = \Security::clean(\Input::post('image-ng'), ['strip_tags', 'htmlentities']) === 'on'?1:0;
			$comment   = \Security::clean(\Input::post('comment'), ['strip_tags', 'htmlentities']);
			$v = \Validation::forge();
			$v->add_field('hash', 'hash', 'required|exact_length[26]|valid_string[alpha,numeric]');
			if ( ! $v->run(['hash' => $hash], true))
			{
				throw new \HttpNotFoundException();
			}

			if (($result = \Libs_Image_Hash::update_by_hash($hash, $ng, $comment)) === null)
			{
				throw new \HttpNotFoundException();
			}

			return $this->response(['status' => 200, 'message'  => 'success', 'reload' => false], 200);
		} catch (\Exception $e) {
			return $this->response(['status' => 403, 'error' => 'forbbiden' , 'message'  => 'error', 'reload' => false], 403);
		}
	}

	public function action_delete()
	{
		throw new \HttpNotFoundException();
	}

	public function post_delete()
	{
		try {
			\Libs_Csrf::check_token();
			$hash = \Security::clean(\Input::post('file'), ['strip_tags', 'htmlentities']);
			$v = \Validation::forge();
			$v->add_field('hash', 'hash', 'required|exact_length[26]|valid_string[alpha,numeric]');
			if ( ! $v->run(['hash' => $hash], true))
			{
				throw new \HttpNotFoundException();
			}

			$images = \Libs_Image::get_by_image_hash($hash);
			if (($result = \Libs_Image::delete_by_images($images)))
			{
				//空配列じゃないのでエラー
				\Log::debug(print_r($result,1));
				return $this->response([
					'status'  => 200,
					'error'   => 'error',
					'message' => 'error',
					'reload'  => true,
				], 200);
			}

			return $this->response([
				'status'   => 200,
				'message'  => 'success',
				'reload'   => true,
			], 200);
		} catch (\Exception $e) {
				return $this->response([
					'status'  => 403,
					'error'   => 'error',
					'message' => 'error',
					'reload'  => true,
				], 403);
		}
	}
}
