<?php
class Libs_Deny_Ip extends Libs_Deny
{
	public static function check($ip)
	{
		return Model_Deny_Ip::find_one_by('ip', $ip);
	}

	public static function enable_post()
	{
		$post = \Libs_Config::get('board.post');
		if (($images = \Model_Image::find([
			'where'    => ['ip'=> \Input::real_ip()],
			'order_by' => ['created_at' => 'desc'],
			'limit'     => 1
		])) === null)
		{
			return true;
		}

		if ((time() - strtotime(reset($images)->created_at)) < $post)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

}
