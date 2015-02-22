<?php

	/*
	|--------------------------------------------------------------------------
	| Users
	|--------------------------------------------------------------------------
	|
	|	Verb 		Path 						Action 		Route Name
	|	------------------------------------------------------------------
	|	GET 		/resource 					index 		resource.index
	|	GET 		/resource/create 			create 		resource.create
	|	POST 		/resource 					store 		resource.store
	|	GET 		/resource/{resource} 		show 		resource.show
	|	GET 		/resource/{resource}/edit 	edit 		resource.edit
	|	PUT/PATCH 	/resource/{resource} 		update 		resource.update
	|	DELETE 		/resource/{resource} 		destroy 	resource.destroy
	*/

class Users extends Resource {

	// public $routes = array('/{id}/deleted' => 'getDeleted');

	public function get() {
		$users = Database::select('SELECT * FROM users');

		// return $this->response->status(200);

		return $this->response->body(200, 'Version 2');
	}

	public function detail($id) {
		return $this->response->body('GET request');
	}

	private function post($id) {
		return $this->response->body('POST request');
	}
}