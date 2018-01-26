<?php
class Model_Image extends Model_Base
{
	protected static $_table_name = 'images';

	protected static $_properties = [
		'basename',
		'ext',
		'original',
		'delkey',
		'mimetype',
		'size',
		'comment',
		'ip',
		'image_hash_id',
	];

	protected static $_rules = ['basename' => 'required'];
}
