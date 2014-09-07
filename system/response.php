<?php

class Response {
	public static $content = array();

	public static function json($array = array(), $status = 200) {
		
		http_response_code($status);

		header('Content-type: application/json');

		return json_encode($array);
	}
}