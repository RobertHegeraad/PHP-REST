<?php

class Response {

	/*
	 * The content type for the response
	 *
	 * @var string
	 */
	private $accept = 'application/json';

	/*
	 * The response body
	 *
	 * @var array
	 */
	private $body = array();

	public function __construct() {
		// Check if the Accept header is allowed, if not use the default text/plain
		if(in_array(Http::$request->accept, Config::get('response', 'http_accept'))) {
			$this->accept = Http::$request->accept;
		}
	}

	/*
	 * Set a header
	 */
	public function header($key, $value) {
		header("$key: $value");
	}

	/*
	 * Creates a response with the status code and items
	 */
	public function body($status = 200, $body = null) {

		// Add the status information to the body
		$this->body['status'] = $this->getStatusCode($status);

		$this->body['items'] = $body;

		$this->getBodyContent();
	}

	/*
	 * Creates a response with a status code and the corresponding message 
	 */
	public function status($status = 200, $message = null) {
		$this->body['status'] = $this->getStatusCode($status, $message);

		$this->getBodyContent();
	}

	/*
	 * Return an array with a status code with the corresponding message
	 */
	private function getStatusCode($status, $message = null) {
		if(isset(Config::get('status')[$status])) {
			if(is_null($message)) {
				$message = Config::get('status')[$status];
			}

			return array(
				'code' => $status,
				'message' => $message,
				'version' => Http::$request->url->version
			);
		} else {
			throw new Exception("Invalid status code");
		}
	}

	/*
	 * Decides the content type for the response depending on the HTTP_ACCEPT header
	 */
	private function getBodyContent() {

		if( ! is_array($this->body)) {
			throw new Exception("Invalid response body");
		}

		switch($this->accept) {
			case 'application/json':
				$this->json();
				break;

			case 'application/xml':
				$this->xml();
				break;

			case 'text/plain' || 'text/html':
				$this->text();
				break;

			default:
				$this->json();
				break;
		}
	}

	/*
	 * Return the body as text
	 */
	private function text() {
		// If the body is an array, return it as a querystring
		if(is_array($this->body)) {
			return http_build_query($this->body);
		} else {
			return $this->body;
		}
	}

	/*
	 * Returnt the body as JSON
	 */
	private function json() {
		try {
			echo json_encode($this->body);
			exit;
		} catch(Exception $e) {
			throw $e;
		}

	}

	/*
	 * Return the body as XML
	 */
	private function xml() {
		return $this->body;
	}
}