<?php

return array(

	/* ----------------------------------------------------------------------------------------------
	 * Status codes
	 * ----------------------------------------------------------------------------------------------
	 *
	 * Common status codes with the corresponding message
	 *
	 * Used in Response::body(int status = 200, string body = null)
	 *
	 * If a status is within the 2** range, the default message will be added to the response body
	 *
	 * Other status codes will have it's default message overridden by the $body variable
	 */

	// 1xx Informational
	'100' => 'Continue',
	'101' => 'Switching Protocols',
	'102' => 'Processing',


	// 2xx Success
	'200' => 'Ok',
	'201' => 'Created',
	'202' => 'Accepted',
	'203' => 'Non-Authoritative Information',
	'204' => 'No content',
	'205' => 'Reset Content',
	'206' => 'Partial Content',
	'207' => 'Multi-Status',
	'208' => 'Already Reported',
	'226' => 'IM Used',
	

	// 3xx Redirection
	'300' => 'Multiple Choices',
	'301' => 'Moved Permanently',
	'302' => 'Found',
	'303' => 'See other',
	'304' => 'Not modified',
	'305' => 'Use Proxy',
	'306' => 'Switch Proxy',
	'307' => 'Temporary Redirect',
	'308' => 'Permanent Redirect',


	// 4xx Client Error
	'400' => 'Bad Request',
	'401' => 'Unauthorized',
	'402' => 'Payment Required',
	'403' => 'Forbidden',
	'404' => 'Not found',
	'405' => 'Method not allowed',
	'406' => 'Not Acceptable',
	'407' => 'Proxy Authentication Required',
	'408' => 'Request timeout',
	'409' => 'Conflict',
	'410' => 'Gone',
	'411' => 'Length Required',
	'412' => 'Precondition Failed',
	'413' => 'Request Entity Too Large',
	'414' => 'Request-URI Too Long',
	'415' => 'Unsupported Media Type',
	'416' => 'Requested Range Not Satisfiable',
	'417' => 'Expectation Failed',
	'419' => 'Authentication Timeout',
	'426' => 'Upgrade Required',
	'429' => 'Too Many Requests',


	// 5xx Server Error
	'500' => 'Internal server error',
	'501' => 'Not Implemented',
	'502' => 'Bad Gateway',
	'503' => 'Service Unavailable',
	'504' => 'Gateway Timeout',
	'505' => 'HTTP Version Not Supported'
);