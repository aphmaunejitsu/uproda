<?php
class Libs_Deny_Word extends Libs_Deny
{
	public static function _validation_not_contain($input)
	{
		try {
			if (empty($input))
			{
				return true;
			}

			$words = Model_Deny_Word::find(['select' => 'word']);
			foreach ($words as $word)
			{
				if (strpos($input, $word->word) !== false)
				{
					return false;
				}
			}

			return true;
		} catch (\Exception $e) {
			\Log::warring($e);
			return true;
		}
	}
}
