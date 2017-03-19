<?php
namespace Nejitsu;
class Controller_Hash extends Controller_Nejitsu
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

		$this->theme->asset->css(['bootstrap-switch.min.css'], [], 'toggle-switch-css', false);
		$this->theme->asset->js(['bootstrap-switch.min.js', 'toggle-switch.js'], [], 'toggle-switch-js', false);

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
		return $this->response(['status' => 200, 'message'  => '保存しました'], 200);
	}

	public function action_delete()
	{
		throw new \HttpNotFoundException();
	}

	public function post_delete()
	{
		return $this->response([
			'status' => 200,
			'image'  => '削除しました'
		], 200);
	}

	public function action_delete_images()
	{
		throw new \HttpNotFoundException();
	}

	public function post_delete_images()
	{
		return $this->response([
			'status' => 200,
			'image'  => 'delete_images'
		], 200);
	}
}
