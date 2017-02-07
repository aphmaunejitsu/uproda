<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return [
	'active'  => 'uproda-slave',
	'uproda-slave' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=127.0.01;dbname=uproda',
			'username'   => 'reader',
			'password'   => 'tekitou'
		],
		'identifier'     => '`',
	    'table_prefix'   => '',
	    'charset'        => 'utf8',
		'profiling'      => false,
	],
	'uproda-master' => [
		'type' => 'pdo',
		'connection'  => [
			'dsn'        => 'mysql:host=127.0.01;dbname=uproda',
			'username'   => 'updater',
			'password'   => 'tekitou'
		],
		'identifier'     => '`',
	    'table_prefix'   => '',
	    'charset'        => 'utf8',
		'profiling'      => false,
	]
];
