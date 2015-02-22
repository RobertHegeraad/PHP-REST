<?php

class Router {
	
	const GET 		= 'GET';
	const PUT 		= 'PUT';
	const POST 		= 'POST';
	const DELETE 	= 'DELETE';
	const OPTIONS 	= 'OPTIONS';
	const PATCH 	= 'PATCH';
	const HEAD 		= 'HEAD';

	const GET_METHOD_NAME 			= 'get';
	const GET_DETAIL_METHOD_NAME 	= 'detail';
	const PUT_METHOD_NAME 			= 'put';
	const POST_METHOD_NAME 			= 'post';
	const DELETE_METHOD_NAME 		= 'delete';
	const OPTIONS_METHOD_NAME 		= 'options';
	const PATCH_METHOD_NAME 		= 'patch';
	const HEAD_METHOD_NAME 			= 'head';

	const VERSION_FOLDER 	= 'version_';
	const RESOURCES_FOLDER 	= 'resources';

	public function __construct($version, $resource, $id, $slug) {

		if(file_exists($filename = APP . self::VERSION_FOLDER . $version . '/' . self::RESOURCES_FOLDER . '/' . $resource . '.php')) {

			require_once $filename;
			
			if(class_exists($resource)) {
				$resourceObject = new $resource(Http::$request, Http::$response);

				Http::$resource = $resourceObject;

				// Attach request and response object to the resource
				$resourceObject->request = Http::$request;
				$resourceObject->response = Http::$response;
				
				$method = $this->point($resourceObject);
				$resourceObject->$method();
			} else {
				Http::$response->status(404);
			}
		} else {
			Http::$response->status(404);
		}
	}

	/*
	 * Point to the correct method
	 */
	public function point($resource) {

		if($resource->request->method(self::GET)) {
			if($resource->request->url->id) {
				$method = self::GET_DETAIL_METHOD_NAME;
			} else {
				$method = self::GET_METHOD_NAME;
			}
		} else if($resource->request->method(self::POST)) {
			$method = self::POST_METHOD_NAME;
		} else if($resource->request->method(self::PUT)) {
			$method = self::PUT_METHOD_NAME;
		} else if($resource->request->method(self::DELETE)) {
			$method = self::DELETE_METHOD_NAME;
		} else if($resource->request->method(self::PATCH)) {
			$method = self::PATCH_METHOD_NAME;
		} else if($resource->request->method(self::OPTIONS)) {
			$method = self::OPTIONS_METHOD_NAME;
		} else if($resource->request->method(self::HEAD)) {
			$method = self::HEAD_METHOD_NAME;
		}

		return $method;
	}
}