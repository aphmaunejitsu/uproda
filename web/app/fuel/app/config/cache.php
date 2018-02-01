<?php
/**
 * Part of the Fuel framework.
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

	/**
	 * ----------------------------------------------------------------------
	 * global settings
	 * ----------------------------------------------------------------------
	 */

	// default storage driver
	'driver'      => 'memcached',

	// default expiration (null = no expiration)
	'expiration'  => null,

	'memcached'  => [
		'cache_id'  => 'uproda-aphmau',  // unique id to distinquish fuel cache items from others stored on the same server(s)
		'servers'   => [   // array of servers and portnumbers that run the memcached service
			'default' => [
				'host' => 'roda-memcached',
				'port' => 11211,
				'weight' => 100
			],
		],
	],
];
