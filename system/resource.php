<?php

class Resource {
	
	/*
	 * Request object
	 */
	public $request;

	/*
	 * Response object
	 */
	public $response;

	public $accept = array(
		'application/json'
	);

	/*
	 * Array containing the allowed method for the resource, will be overridden by the config/resources array
	 *
	 * @var array
	 */
	public $options = array('GET');



	public function __construct(Request $request, Response $response) {
		$this->request = $request;
		$this->response = $response;

		$options = Config::get('resources', $this->request->url->resource);
		if($options !== false) {			
			if($this->request->url->id === false) {
				$this->options = $options['/'];
			} else {
				$this->options = $options['/{id}'];
			}

			if( ! in_array($this->request->method, $this->options)) {
				$this->response->status(405);
			}

			// Get the input for the request
			Http::$request->getInput();
		}
	}

	/*
	 * Returns the available request methods for the resource
	 */
	final public function options() {
		$this->response->header('Allow', implode(', ', $this->options));

		$this->response->status(200);
	}

	/*
	 * Returns a HEAD response
	 */
	final public function head() {
		return $this->response->status(200);
	}

	/*
	 * Handle wrongly called methods
	 */
	public function __call($name, $arguments) {
		$this->response->status(404);
	}
}