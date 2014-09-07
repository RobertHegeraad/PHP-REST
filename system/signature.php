<?php

class Signature
{
	/**
	 * Create the signature and return either a querystring or an array depending on the request method that will be used
	 *
	 * @param array The required data to create the signature
	 *
	 * $data['method'] The request method that will be used
	 * $data['resource'] The resource that will be called (website.com/users, in this case it is users)
	 * $data['public_key'] The public key for the user that will make the request
	 * $data['parameters'] The input that will be send with the request
	 */
	public static function create($data) {
		if( ! isset($data['parameters'])) $data['parameters'] = array();

		$timestamp = time();

		// Append the items
		$signature = '';
		$signature .= self::canonicalizeQuerystring($data['parameters']);
		$signature .= '&date=' . self::getDate();
		$signature .= '&method=' . $data['method'];
		$signature .= '&resource=' . $data['resource'];
		$signature .= '&public=' . $data['public_key'];

		// Base64 encode and sha256 hash the signature
		$signature = base64_encode(hash_hmac('sha256', $signature, $data['private_key'], true));


		// If the request method is GET,
		// return a querystring with the parameters, public key, signature and timestamp
		if(strtoupper($data['method']) === 'GET') {
			return self::getQuerystring($data['parameters']) .
						'&public=' . $data['public_key'] .
						'&signature=' . str_replace("%7E", "~", rawurlencode($signature)) .
						'&stamp=' . $timestamp;
		}

		// If the request method is POST/PUT,
		// return an array with the parameters, public key, signature and timestamp
		return array_merge(array(
			'public' => $data['public_key'],
			'signature' => $signature,
			'stamp' => $timestamp,
		), $data['parameters']);
	}


	/**
	 * Create the signature and return either a querystring or an array depending on the request method that will be used
	 *
	 * @param array The required data to create the signature
	 *
	 * $data['method'] The current used request method
	 * $data['resource'] The current resource in the URI
	 * $data['public_key'] The public key for the user that made the request
	 * $data['parameters'] The input that was sent with the request
	 */
	public static function check($data) {
		$signature = '';
		$signature .= self::canonicalizeQuerystring($data['parameters']);
		$signature .= '&date=' . self::getDate();
		$signature .= '&method=' . strtolower(self::getRequestMethod());
		$signature .= '&resource=' . $data['resource'];
		$signature .= '&public=' . $data['public_key'];

		$signature = base64_encode(hash_hmac('sha256', $signature, $data['private_key'], true));

		return self::compare($signature, $data['signature']);
	}

	/**
	 * Compare the sent signature ($b) with the recreated signature ($a) by checking if both hashes are string, the same length
	 * and finally compare the with strcmp() function
	 *
	 * @param The recreated signature
	 * @param The sent signature with the request
	 */
	protected static function compare($a, $b) {
		if( ! is_string($a) || ! is_string($b))
			return false;

		if(strlen($a) !== strlen($b))
			return false;

		if(strcmp($a, $b) !== 0)
			return false;

		return true;
	}

	/*
	 * Canonicalize the query string for the request according to the AWS rules
	 *
	 * http://docs.aws.amazon.com/general/latest/gr/sigv4-create-canonical-request.html
	 *
	 * a URI-encode each parameter name and value according to the following rules:
	 *   - Do not URL-encode any of the unreserved characters that RFC 3986 defines: A-Z, a-z, 0-9, hyphen ( - ), underscore ( _ ), period ( . ), and tilde ( ~ ).
	 *   - Percent-encode all other characters with %XY, where X and Y are hexadecimal characters (0-9 and uppercase A-F).
	 *   - For example, the space character must be encoded as %20 (not using '+', as some encoding schemes do) and extended UTF-8 characters must be in the form %XY%ZA%BC.
	 * b Sort the encoded parameter names by character code (that is, in strict ASCII order). For example, a parameter name that begins with the uppercase letter F (ASCII code 70) precedes a parameter name that begins with a lowercase letter b (ASCII code 98).
	 * c Build the canonical query string by starting with the first parameter name in the sorted list.
	 * d For each parameter, append the URI-encoded parameter name, followed by the character '=' (ASCII code 61), followed by the URI-encoded parameter value.
	 * 	- Use an empty string for parameters that have no value.
	 * e Append the character '&' (ASCII code 38) after each parameter value except for the last value in the list.
	 *
	 * @param p Associative array of parameters
	 */
	protected static function canonicalizeQuerystring($p) {
		$canonicalized_query = [];

		// todo: Check for empty values

		foreach($p as $k => $v) {
			$k = str_replace("%7E", "~", rawurlencode($k));
			$v = str_replace("%7E", "~", rawurlencode($v));   
		}

		uksort($p, 'self::sortByACII');

		return self::getQuerystring($p);
	}

	/**
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

	/**
	 * Get the current date
	 */
	protected static function getDate() {
		return date('Ymd');
	}

	/**
	 * Get the used request method 
	 */
	protected static function getRequestMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Get the URI for the current request
	 */
	protected static function getURI() {
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * Sort the keys of the array in ascending order by their ASCII code
	 */
	protected static function sortByACII($a, $b) {
		// todo: check if ASCII code exists
		$a = ord($a);
		$b = ord($b);

		if($a > $b) return 1;
		if($a < $b) return -1;
		if($a == $b) return 0;
	}
}