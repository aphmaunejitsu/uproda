<?php
/**
 CREATE TABLE `images` (
 `id` bigint(20) NOT NULL AUTO_INCREMENT,
 `basename` varchar(100) DEFAULT NULL,
 `ext` varchar(10) DEFAULT NULL,
 `original` varchar(100) DEFAULT NULL,
 `delkey` varchar(20) DEFAULT NULL,
 `mimetype` varchar(20) DEFAULT NULL,
 `size` int(10) DEFAULT NULL,
 `comment` text,
 `ip` varchar(20) DEFAULT NULL,
 `ng` int(10) DEFAULT NULL,
 `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 KEY `idx_basename` (`basename`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8
**/
class Model_Image extends Model_Base
{
	protected static $_table_name = 'images';
	protected static $_connection = 'uproda-slave';
	protected static $_write_connection = 'uproda-master';

	protected static $_properties = [
		'basename',
		'ext',
		'original',
		'delkey',
		'mimetype',
		'size',
		'comment',
		'ip',
		'ng'
	];

	protected static $_rules = ['basename' => 'required'];
}
