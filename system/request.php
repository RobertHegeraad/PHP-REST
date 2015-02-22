<?php

class Request {

	/*
	 * @var string
	 */
	public $method;

	/*
	 * The timestamp of the request
	 *
	 * @var int
	 */
	public $timestamp;

	/*
	 * The content type for the request
	 *
	 * @var string
	 */
	public $content_type = 'application/x-www-form-urlencoded';

	/*
	 * The accept type for the request
	 *
	 * @var string
	 */
	public $accept = 'application/json';

	/*
	 * Array containg the request body
	 *
	 * @var array
	 */
	public $input = array();

	/*
	 * The Url class
	 *
	 * @var object
	 */
	public $url;

	/*
	 * Whether the request was made with AJAX or not
	 *
	 * @var bool
	 */
	public $ajax = false;


	public function __construct() {

		$this->url = new Url();

		$this->timestamp 	= (isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : false);
		$this->accept 		= (isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : 'application/json');
		$this->method 		= (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false);
		$this->origin		= (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false);

		$this->ajax = $this->isAjax();
		
		if($this->ajax === true) {
			$this->content_type = 'application/json';
		} else {
			$this->content_type = (isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : 'application/x-www-form-urlencoded');
		}
	}

	/*
	 * Get the correct input depending on the Request method and the Content-Type
	 */
	public function getInput() {
		$input = [];

		$types = Config::get('request', 'content_type');

		if($types !== false) {
			if(in_array($this->content_type, $types)) {
				// If it was a GET request use the $_GET superglobal,
				// same goes for a POST request with $_POST
				// For PUT request use the PHP input stream
				if($this->method('get')) {
				    $input = $_GET;
				} else if($this->method('post')) {
					$input = $_POST;
				    
				    if($this->content_type == 'application/json') {
				        $contents = file_get_contents("php://input");
				        parse_str($contents, $input);

				        // $contents = file_get_contents("php://input");
				        // $input = json_decode($contents, true);
				    }				    
				} else if($this->method('put')) {
			        $contents = file_get_contents("php://input");
			        parse_str($contents, $input);
				}

				$this->input = $input;
			} else {
				Http::$response->status(400);
			}
		}
	}

	/*
	 * Check if the request was made with AJAX
	 */
	private function isAjax() {
		// http://davidwalsh.name/detect-ajax
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return true;
		}

		return false;
	}

	/*
	 * Checks if a certain request method was used
	 */
	public function method($method) {
		if(strtolower($this->method) == strtolower($method)) {
			return true;
		}

		return false;
	}
}