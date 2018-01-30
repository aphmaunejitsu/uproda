<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return [
	'active' => 'uproda-slave',
	'uproda-master' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=roda-db;dbname=uproda',
			'port'       => 23306,
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
			'dsn'        => 'mysql:host=roda-db;dbname=uproda',
			'port'       => 23306,
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
			'dsn'        => 'mysql:host=roda-db;dbname=uproda',
			'port'       => 23306,
			'username'   => 'maintenance',
			'password'   => 'tekitou'
		],
		'identifier'     => '`',
	  'table_prefix'   => '',
	  'charset'        => 'utf8',
		'profiling'      => false,
	],

	//Redis
	'redis' => [
		'default' => [
			'hostname' => 'roda-redis',
			'port'     => 6379,
			'timeout'	 => null,
			'database' => 0,
		],
	],
];
