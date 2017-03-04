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

return array(

	/**
	 * DB connection, leave null to use default
	 */
	'db_connection' => null,

	/**
	 * DB write connection, leave null to use same value as db_connection
	 */
	'db_write_connection' => null,

	/**
	 * DB table name for the user table
	 */
	'table_name' => 'users',

	/**
	 * Array, choose which columns from the users table are selected.
	 *  must include: username, password, email, last_login,
	 * login_hash, group & profile_fields
	 */
	'table_columns' => null,

	/**
	 * This will allow you to use the group & acl driver for non-logged in users
	 */
	'guest_login' => false,

	/**
	 * This will allow the same user to be logged in multiple times.
	 *
	 * Note that this is less secure, as session hijacking countermeasures have to
	 * be disabled for this to work!
	 */
	'multiple_logins' => false,

	/**
	 * Remember-me functionality
	 */
	'remember_me' => [
		/**
		 * Whether or not remember me functionality is enabled
		 */
		'enabled' => true,

		/**
		 * Name of the cookie used to record this functionality
		 */
		'cookie_name' => 'baajonnappu',

		/**
		 * Remember me expiration (default: 31 days)
		 */
		'expiration' => 86400 * 31,
	],

	/**
	 * Groups as id => array(name => <string>, roles => <array>)
	 */
	'groups' => [
		 -1   => ['name' => 'Banned',         'roles' => ['banned']],
		 0    => ['name' => 'Guests',         'roles' => []],
		 1    => ['name' => 'Users',          'roles' => ['user']],
		 50   => ['name' => 'Moderators',     'roles' => ['user', 'moderator']],
		 100  => ['name' => 'Administrators', 'roles' => ['user', 'moderator', 'admin']],
	],

	/**
	 * Roles as name => array(location => rights)
	 */
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

	/**
	 * Salt for the login hash
	 */
	'login_hash_salt' => 'sasuganinjakitanai',

	/**
	 * $_POST key for login username
	 */
	'username_post_key' => 'namae',

	/**
	 * $_POST key for login password
	 */
	'password_post_key' => 'pasuwaado',
);
