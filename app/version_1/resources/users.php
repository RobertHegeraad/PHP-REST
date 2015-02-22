<?php

class Users extends Resource {

	public $accept = array(
		'text/plain',
		'text/html',
		'application/json'
	);

	/*
	 * GET /users
	 */
	public function get() {
		$this->accept = array('application/jsons');

		$users = Database::select('SELECT * FROM users');

		return $this->response->body(200, $this->request->input);
	}

	/*
	 * GET /users/1
	 */
	public function detail() {
		return $this->response->body(200, $this->request->url->id);
	}

	/*
	 * POST /users
	 */
	public function post() {
		return $this->response->body(201, $this->request->input);
	}

	/*
	 * PUT /users/1
	 */
	public function put() {
		return $this->response->body(200, $this->request->url->id);
	}

	/*
	 * PATCH /users/1
	 */
	public function patch() {
		return $this->response->body(200, $this->request->url->id);
	}

	/*
	 * DELETE /users/1
	 */
	public function delete() {
		return $this->response->body(204, $this->request->url->id);
	}
}