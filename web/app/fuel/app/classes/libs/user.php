<?php
class Libs_User_Exception extends \Libs_Exception {}

class Libs_User
{
	/**
	 * 指定されたハッシュからユーザーを取得する
	 *
	 * @param string $hash ハッシュ
	 *
	 * @return Model_User or Null
	 **/
	public static function get_by_hash($hash)
	{
		try {
			$users = \Model_User::find(function (&$query) use ($hash) {
				$key = \Libs_Config::get('board.key');
				return $query->where(\DB::expr('sha1(concat('."'".$key."'".',id))'), $hash);
			});

			if ( ! $users)
			{
				return false;
			}

			$user = reset($users);

			return $user;
		} catch (\Exception $e) {
			\Log::error($e);
			return false;
		}
	}
}
