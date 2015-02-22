<?php

class Url {

	/*
	 * The request scheme (http / https)
	 *
	 * @var string
	 */
	public $scheme = 'http';

	/*
	 * The full URL
	 *
	 * @var string
	 */
	public $url;

	/*
	 * The URI as a string
	 *
	 * @var string
	 */
	public $uri;
	
	/*
	 * The parameters from the querystring
	 *
	 * @var array
	 */
	public $query;
	
	/*
	 * The version for the api
	 *
	 * @var int
	 */
	public $version = 1;

	/*
	 * The resource identifier
	 *
	 * @var string
	 */
	public $resource;
	
	/*
	 * The ID for the resource
	 *
	 * @var int
	 */
	public $id;

	/*
	 * The slug
	 *
	 * @var string
	 */
	public $slug;

	/*
	 * The host
	 *
	 * @var string
	 */
	public $host;

	
	public function __construct() {
		
		$this->getUri();

		// Set the parameters from the querystring
		$this->query = array_filter($_GET);

		// Set the full URL
		// $this->url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		$this->scheme 		= (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : false);
		$this->host 		= (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : false);
	}

	/*
	 * Get information from the URI
	 */
	protected function getUri() {
		// Set the URI retrieved from the request parameter
		if(isset($_GET['request'])) {
			// Break up the segments at '/',
			// decode the URI
			// and remove any empty values
			$segments = array_filter(explode('/', urldecode($_GET['request'])));

			// Set each individual part of the URI
			if(isset($segments[0])) {
				if(preg_match('/^[0-9]$/', $segments[0])) {
					$this->version = $segments[0];
					$this->resource = $segments[1];
					$this->id 		= (isset($segments[2]) ? $segments[2] : false);
					$this->slug 	= (isset($segments[3]) ? $segments[3] : false);
				} else {
					$this->resource = $segments[0];
					$this->id 		= (isset($segments[1]) ? $segments[1] : false);
					$this->slug 	= (isset($segments[2]) ? $segments[2] : false);
				}
			}

			// Set the complete URI string
			$this->uri = $_GET['request'];

			unset($_GET['request']);
		}
	}

	/*
	 * Canonicalize the querystring for the URL
	 */
	protected function canonicalize() {

	}
}