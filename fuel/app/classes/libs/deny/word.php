<?php
class Libs_Deny_Word extends Libs_Deny
{
	public static function _validation_not_contain($input)
	{
		if (empty($input))
		{
			return true;
		}

		Libs_Config::load('spamwords');
		$words = Libs_Config::get('words');

		foreach ($words as $word)
		{
			if ( strpos($input, $word))
			{
				return false;
			}
		}

		return true;
	}
}
