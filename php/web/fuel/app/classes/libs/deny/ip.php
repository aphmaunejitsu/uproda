<?php
class Libs_Deny_Ip extends Libs_Deny
{
	const NOERROR = 0;
	const ERROR_STRAIGHT = 1;

	/**
	 * 拒否IPではないかのチェック
	 *
	 * @param string $ip IPアドレス
	 * @return boolean 拒否IP以外: true
	 * @throws Libs_Deny_Ip_Exception 拒否IPの場合
	 **/
	public static function check($ip)
	{
		if (Model_Deny_Ip::find_one_by('ip', $ip))
		{
			throw new Libs_Deny_Ip_Exception('アクセス拒否IP: '.$ip, __LINE__);
		}

		return true;
	}

	/**
	 * ポスト可能か調べる(連投規制対応)
	 *
	 * @return booelan ポスト可能: true
	 * @throws Libs_Deny_Ip_Exception 連投規制にひっかかった
	 **/
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
			throw new Libs_Deny_Ip_Exception('連投規制: '.\Input::real_ip(), self::ERROR_STRAIGHT);
		}

		return true;
	}
}

class Libs_Deny_Ip_Exception extends Libs_Exception {}

