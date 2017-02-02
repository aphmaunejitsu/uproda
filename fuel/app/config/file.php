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

return array(

	// The default File_Area config
	'base_config' => array(
		'basedir'  => null,
		'extensions'  => null,
		'url'  => null,
		'use_locks'  => null,
		'file_handlers'  => array(),
	),

	// Pre configure some areas
	'areas' => array(
		'uproda' => [
			'basedir'    => DOCROOT.'uploads',
			'use_locks'  => true,
 		],
		/* 'area_name' => array(
			'basedir'        => null,
			'extensions'     => null,
			'url'            => null,
			'use_locks'      => null,
			'file_handlers'  => array(),
		), */
	),

	// fileinfo() magic filename
	'magic_file' => null,

	// default file and directory permissions
	'chmod' => array(

		/**
		 * Permissions for newly created files
		 */
		'files'  => 0666,

		/**
		 * Permissions for newly created directories
		 */
		'folders'  => 0777,
	),

);
