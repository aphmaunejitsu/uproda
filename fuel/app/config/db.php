<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return [
	//意味はないけど、将来大規模になったらM/S構成に
	'active' => 'uproda-master',
	'uproda-master' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=127.0.0.1;dbname=uproda',
			'username'   => 'updater',
			'password'   => 'tekitou'
		],
		'identifier'     => '`',
	    'table_prefix'   => '',
	    'charset'        => 'utf8',
		'profiling'      => false,
		'readonly' => ['uproda-slave'],
	],
	'uproda-slave' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=127.0.0.1;dbname=uproda',
			'username'   => 'reader',
			'password'   => 'tekitou'
		],
		'identifier'     => '`',
	    'table_prefix'   => '',
	    'charset'        => 'utf8',
		'profiling'      => false,
	],
	//マイグレーション用
	'uproda-maintenance' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=127.0.0.1;dbname=uproda',
			'username'   => 'maintenance',
			'password'   => 'tekitou'
		],
		'identifier'     => '`',
	    'table_prefix'   => '',
	    'charset'        => 'utf8',
		'profiling'      => false,
	],
];
