<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return [
	//意味はないけど、将来大規模になったらM/S構成に
	'active' => 'uproda-maintenance',
	'uproda-master' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=127.0.0.1;dbname=uproda',
			'username'   => 'updater',
			'password'   => 'tekitou'
		],
		'identifier'     => '',
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
		'identifier'     => '',
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
	//ユーザ管理
	'admin-master' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=127.0.0.1;dbname=uproda_admin',
			'username'   => 'admin_updater',
			'password'   => 'tekitou'
		],
		'identifier'     => '',
	    'table_prefix'   => '',
	    'charset'        => 'utf8',
		'profiling'      => false,
		'readonly' => ['admin-slave'],
	],
	'admin-slave' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=127.0.0.1;dbname=uproda_admin',
			'username'   => 'admin_reader',
			'password'   => 'tekitou'
		],
		'identifier'     => '',
	    'table_prefix'   => '',
	    'charset'        => 'utf8',
		'profiling'      => false,
	],
	//マイグレーション用
	'admin-maintenance' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=127.0.0.1;dbname=uproda_admin',
			'username'   => 'admin_maintenance',
			'password'   => 'tekitou'
		],
		'identifier'     => '`',
	    'table_prefix'   => '',
	    'charset'        => 'utf8',
		'profiling'      => false,
	],
];
