<?php

/* ----------------------------------------------------------------------------------------------
 * FILE INFORMATION
 * ----------------------------------------------------------------------------------------------
 *
 * FILE: index.php
 *
 * LOCATION: ./index.php
 *
 * Contains global settings for the application.
 */



/* ----------------------------------------------------------------------------------------------
 * Autoloader
 * ----------------------------------------------------------------------------------------------
 *
 * Include the Autoloader class to handle the use of libraries.
 */

require_once 'vendor/autoload.php';



/* ----------------------------------------------------------------------------------------------
 * Set path constants
 * ----------------------------------------------------------------------------------------------
 *
 * Set path constants for some folders.
 */


define('SYSTEM',    'system/');
define('APP',       'app/');
define('ROUTES',    APP . 'routes/');
define('CONFIG', 	APP . 'config/');



/* ----------------------------------------------------------------------------------------------
 * Config
 * ----------------------------------------------------------------------------------------------
 *
 * Include the Config class to handle calls to config files.
 */

require_once(SYSTEM . 'config.php');



/* ----------------------------------------------------------------------------------------------
 * Database
 * ----------------------------------------------------------------------------------------------
 *
 * Connect to the datbase using the Database class
 */

$connections = Config::get('database', 'connections');

Database::connect($connections['mysql']);



/* ----------------------------------------------------------------------------------------------
 * Request
 * ----------------------------------------------------------------------------------------------
 *
 * Parse the request, making all the request information available via the Request class
 */

Request::parse();



/*-----------------------------------------------------------------------------------------------
 * Allowed hosts
 * ----------------------------------------------------------------------------------------------
 *
 * See if the origin request is allowed by checking the app/config/cors.php allowed_domains array
 */

// Check if the origin is allowed
if(isset($_SERVER['HTTP_ORIGIN'])) {
    $origin = $_SERVER['HTTP_ORIGIN'];

    // If the origin is found in the allowed domains array set the header to allow this origin
    if(in_array($origin, Config::get('cors', 'allowed_domains'))) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        echo Response::json(array(
            'error' => 'Origin not allowed',
            'status' => 401), 200
        );
        exit;
    }
}

// Only allow GET, POST, PUT and DELETE
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');    // OPTIONS for preflight requests
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');



/*-----------------------------------------------------------------------------------------------
 * User authorization with public and private key
 * ----------------------------------------------------------------------------------------------
 * 
 * Check the Public API key with the Private API key
 *
 * http://www.thebuzzmedia.com/designing-a-secure-rest-api-without-oauth-authentication/
 */

// If it was a GET request use the $_GET superglobal,
// same goes for a POST request with $_POST
// For PUT request use the PHP input stream
if(Request::method('get')) {
    $input = $_GET;
} else if(Request::method('post')) {
    $input = $_POST;
} else {
    parse_str(file_get_contents("php://input"), $input);
}



// Are all the required authentication parameters set?
if( ! isset($input['public'], $input['signature'], $input['stamp'])) {
    echo Response::json(array(
        'error' => 'Unauthorized input',
        'status' => 401,
        'input' => $input,
        'method' => Request::method()), 200
    );
    exit;
}


// Was the request made less then 5 minutes ago? Protects against replay attacks
if((time() - $input['stamp']) / 60 < 5 || $input['stamp'] > time()) {

    // Drop the signature, timestamp and the public key from the input array so only the parameters remain
    $sent_signature = $input['signature']; unset($input['signature']);
    $public    = $input['public']; unset($input['public']);
    $timestamp = $input['stamp']; unset($input['stamp']);


    // Recreate the signature that was sent with the values of the current request
    $recreated_signature = Signature::check(array(
        'public_key'    => $public,
        'private_key'   => '02290a862b16082fd64a1a9799332d8e6cdc12050b1f2c19a367f6ee1e3c0de6',    // Private key should be retrieved from the database
        'parameters'    => $input,
        'method'        => Request::method(),
        'resource'      => Request::segment(1),
        'signature'     => $sent_signature
    ));

    // If the recreated signature didn't match the sent signature
    if($recreated_signature === false) {
        echo Response::json(array(
            'error'     => 'Unauthorized signature',
            'status'    => 401,
            'input'     => $input,
            'method'    => Request::method(),
            'signature' => $input,
            'resource'  => Request::segment(1)), 200
        );
        exit;
    }
} else {
    echo Response::json(array(
        'error' => 'Timed out',
        'status' => 408), 200
    );
    exit;
}



/*-----------------------------------------------------------------------------------------------
 * Instantiate
 * ----------------------------------------------------------------------------------------------
 *
 * Instantiate the class for this request and call the correct method
 */

$resource = Request::segment(1);
$parameter = Request::segment(2);

// Create a stdClass that will be send to the REST method and that holds information about the request
$request = new stdClass();
$request->resource = $resource;
$request->parameter = $parameter;
$request->input = $input;



// Require the class if it exists
if(file_exists($path = ROUTES . $resource . '.php')) {

    require_once $path;


    // Check which pattern the current URI has, e.g. if it has a resource ID

    $route = '';

    if(Request::segment(2) !== FALSE) {
        $route .= '{resource}';

        if(Request::segment(3) !== FALSE) {
            $route .= '/' . Request::segment(3);
        }
    }


    // Does the current URI exist as a route for this request method?
    if(isset(Config::get('routes', Request::method())[$route])) {

        $method = Config::get('routes', Request::method())[$route];

        if(method_exists($resource, $method)) {

            echo $resource::$method($request);
            exit;
        }
    }

    echo Response::json(array(
        'error' => 'Route not found',
        'status' => 404), 200
    );
    exit;
}