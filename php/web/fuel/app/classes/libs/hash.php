<?php
class Libs_Hash
{
	const MAGICCODE = 'desushiosushi';
	public static function crypt($id)
	{
		$mc = \Libs_Config::get('board.key', self::MAGICCODE);
		return sha1($mc.$id);
	}
}
