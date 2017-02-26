<?php
class Model_Image_Hash extends Model_Base
{
	protected static $_table_name = 'image_hash';

	protected static $_properties = [
		'hash',
		'ng',
		'comment',
	];

	protected static $_rules = ['hash' => 'required'];
}
