<?php
class Libs_Deny_Word extends Libs_Deny
{
	public static function check($input)
	{
			$v = \Validation::forge();
			$v->add_callable('Libs_Deny_Word');
			$v->add('words', 'deny words')->add_rule('not_contain');
			if ( ! $v->run(['words' => $input], true))
			{
				throw new Libs_Deny_Word_Exception('拒否文字列が含まれている', __LINE__);
			}
	}

	public static function _validation_not_contain($input)
	{
		try {
			if (empty($input))
			{
				return true;
			}

			if (($words = Model_Deny_Word::find(['select' => 'word']) === null))
			{
				return true;
			}

			foreach ($words as $word)
			{
				if (strpos($input, $word->word) !== false)
				{
					return false;
				}
			}

			return true;
		} catch (\Exception $e) {
			\Log::warning($e);
			return true;
		}
	}
}

class Libs_Deny_Word_Exception extends Libs_Exception {}
