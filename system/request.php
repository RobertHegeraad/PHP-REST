<?php

class Request
{
	/**
	 * The parameters from the querystring
	 *
	 * @var array
	 */
	protected static $parameters = array();

	/**
	 * @var string
	 */
	protected static $method = false;

	/**
	 * URI segments in an array, default value is 'index' in case an empty URI is set
	 *
	 * @var array
	 */
	protected static $segments = array('index');

	/**
	 * The URI as a string
	 *
	 * @var string
	 */
	protected static $uri = false;

	/**
	 * The full URL
	 *
	 * @var string
	 */
	protected static $url = false;

	/**
	 * The timestamp of the request
	 *
	 * @var int
	 */
	protected static $timestamp = false;



	/** ------------------------------------------------------------------------------------------------------
	 * Parse the URL and set all the properties containing information about the URL
	 */
	public static function parse() {

		// Set the URI retrieved from the request parameter
		if(isset($_GET['request'])) {
			// Break up the segments at '/',
			// decode the URI
			// and remove any empty values
			$segments = array_filter(explode('/', urldecode($_GET['request'])));

			// Set the URI segments array
			self::$segments = $segments;


			// Set the URI string
			self::$uri = $_GET['request'];


			unset($_GET['request']);
		}
	

		// Set the parameters from the querystring
		self::$parameters = array_filter($_GET);


		// Set the request method
		self::$method = $_SERVER['REQUEST_METHOD'];


		// Set the full URL
		self::$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];


		// Set the timestamp
		self::$timestamp = $_SERVER['REQUEST_TIME'];

		return self::$uri;
	}

	/** ------------------------------------------------------------------------------------------------------
	 * Get a specific segment from the URI
	 *
	 * The first segment is 1
	 * The second segment is 2 etc
	 *
	 * Returns false if no segments exist
	 */
	public static function segment($index = 1) {
		if(isset(self::$segments[$index - 1])) {
			return self::$segments[$index - 1];
		}

		return false;
	}

	/** ------------------------------------------------------------------------------------------------------
	 * Return all the URI segments in an array
	 */
	public static function segments() {
		return self::$segments;
	}

	/** ------------------------------------------------------------------------------------------------------
	 * Create's a querystring from an associative array
	 *
	 * @param array The array with the key value pairs
	 */
	protected static function getQuerystring($p) {
		$query = [];
		foreach($p as $k => $v) {
			$query[] = $k . "=" . $v;	
		}

		return implode("&", $query);
	}

	/** ------------------------------------------------------------------------------------------------------
	 * Get the request method or check if a specific request method was used
	 *
	 * @param string/bool the method to check for
	 */
	public static function method($method = false) {
		if( ! $method) {
			return self::$method;	
		}

		if(self::$method == strtoupper($method)) {
			return true;
		}

		return false;
	}

	/** ------------------------------------------------------------------------------------------------------
	 * Get the URI
	 */
	public static function uri() {
		return self::$uri;
	}	

	/** ------------------------------------------------------------------------------------------------------
	 * Get the full URL
	 */
	public static function url() {
		return self::$url;
	}

	/** ------------------------------------------------------------------------------------------------------
	 * Get the current timestamp
	 */
	protected static function timestamp() {
		return self::$timestamp;
	}
}