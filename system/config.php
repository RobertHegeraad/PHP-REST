<?php

/* ----------------------------------------------------------------------------------------------
 * FILE INFORMATION
 * ----------------------------------------------------------------------------------------------
 *
 * FILE: config.php
 *
 * LOCATION: ./system/config.php
 *
 * Fetches data from the configuration files in application/config/
 */



class Config
{
	/**
	 * All the loaded config items are cached in this array
	 *
	 * @var  array
	 */
	protected static $items = array();



	/** -----------------------------------------------------------------------------------------------
	 * Get a specific key from a config array or get the entire config array
	 *
	 * 		// Get an enire array
	 *		Config::get('database');
	 *
	 * 		// Get a key from a config array
	 *		Config::get('database', 'connections');
	 *
	 * @param  string  $file  The config file to get data from
	 * @param  string  $key   The key in the config array to select
	 */
	public static function get($file, $key = FALSE) {
		// Does the key already exist in the $items array?
		if($key !== FALSE && isset(static::$items[$file][$key])) {
			// Return the key value
			return static::$items[$file][$key];
		}

		// Does the config file exists?
		if(file_exists($path = CONFIG . $file . '.php')) {
			// Include the config path, this will return the config array
			$config = require $path;

			// Is the user requesting a specific key from the config array?
			if($key !== FALSE) {
				// Cache the retrieved values
				static::$items[$file][$key] = $config[$key];

                return static::$items[$file][$key];
			}
			else {
				// Cache the retrieved config array
				static::$items[$file] = $config;

				return static::$items[$file];
			}
		}

		return FALSE;
	}
}