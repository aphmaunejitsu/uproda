<?php
class Libs_Deny_Ip extends Libs_Deny
{
	public static function check($ip)
	{
		return Model_Deny_Ip::find_one_by('ip', $ip);
	}
}
