<?php
/**
 * コメントの拒否文字列
 *
 */
class Model_Deny_Word extends Model_Base
{
	protected static $_table_name = 'deny_words';

	protected static $_properties = [
		'word',
	];

	protected static $_rules = ['word' => 'required'];
}

