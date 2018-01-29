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
	 * 連投規制用のIPを登録する
	 *
	 * @param string $ip IPアドレス
	 * @param int $expire 連投制限時間 default 60秒
	 */
	public static function set_ip($ip, $expire = 60)
	{
		$redis = \Libs_Redis::forge();
		//既にキーが登録されているので無視
		if ($data = $redis->get($ip))
		{
			return;
		}

		$post = \Libs_Config::get('board.post', $expire);
		$redis->setex($ip, $post, $ip);
	}

	/**
	 * ポスト可能か調べる(連投規制対応)
	 *
	 * @return booelan ポスト可能: true
	 * @throws Libs_Deny_Ip_Exception 連投規制にひっかかった
	 **/
	public static function enable_post($ip)
	{
		$redis = \Libs_Redis::forge();

		if ($data = $redis->get($ip))
		{
			throw new Libs_Deny_Ip_Exception('連投規制: '.$ip, self::ERROR_STRAIGHT);
		}

		return true;
	}
}

class Libs_Deny_Ip_Exception extends Libs_Exception {}

