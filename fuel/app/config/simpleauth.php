<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */

return [
	'db_connection' => 'uproda-slave',
	'db_write_connection' => 'uproda-master',
	'table_name' => 'users',
	'table_columns' => null,
	'guest_login' => false,
	'multiple_logins' => false,
	'remember_me' => [
		'enabled' => false,
		'cookie_name' => 'baajonnappu',
		'expiration' => 86400 * 31,
	],
	'groups' => [
		 -1   => ['name' => 'Banned',         'roles' => ['banned']],
		 0    => ['name' => 'Guests',         'roles' => []],
		 1    => ['name' => 'Users',          'roles' => ['user']],
		 50   => ['name' => 'Moderators',     'roles' => ['user', 'moderator']],
		 100  => ['name' => 'Administrators', 'roles' => ['user', 'moderator', 'admin']],
	],
	'roles' => array(
		/**
		 * Examples
		 * ---
		 *
		 * Regular example with role "user" given create & read rights on "comments":
		 *   'user'  => array('comments' => array('create', 'read')),
		 * And similar additional rights for moderators:
		 *   'moderator'  => array('comments' => array('update', 'delete')),
		 *
		 * Wildcard # role (auto assigned to all groups):
		 *   '#'  => array('website' => array('read'))
		 *
		 * Global disallow by assigning false to a role:
		 *   'banned' => false,
		 *
		 * Global allow by assigning true to a role (use with care!):
		 *   'super' => true,
		 */
	),
	'login_hash_salt' => 'sasuganinjakitanai',
	'username_post_key' => 'namae',
	'password_post_key' => 'pasuwaado',
];
