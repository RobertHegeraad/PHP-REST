PHP Basic REST setup
====

## Routes

Location: ./app/routes/

The routes folder contains all your classes that can be called with a request. A GET request to 'www.restwebservice.com/users/' will call the index method in the users.php file.
More information about route mapping can be found in the Route settings section.

The methods in the route classes accept a $request object as a parameter, this object contains the following information about the request:
the resource (in this case user), the parameter (the third part of the URI) and the sent input as an array.

Here is a method example that retrieves a user from the database by the supplied ID in the URI and returns the user data as JSON with the Response class

```php
public function show($request)
{
	// Get a single user by the provided ID
	$user = Database::select("SELECT * FROM users WHERE user_id = ?", array($request->parameter), 'ASSOC');


	return Response::json(array('user' => $user), 200); 	// Return data with the response code 200 (OK)
}
```

## Public / Private key authentication

For each request a signature must be created with the Signature class located in ./system/signature.php.
To create a signature call the create() method and pass an array containg a public key, a private key, the resource that will be requested, the request method that will be used, the date and the parameters that will be send with the request.

```php
// Create a signature for the PUT request
$signature = Signature::create(array(
	'public_key' => '761146773916776',		// Can be a user ID for the logged in user
	'private_key' => '02290a862b16082fd64a1a9799332d8e6cdc12050b1f2c19a367f6ee1e3c0de6',	// Normally retrieved from the database
	'parameters' => array('name' => 'Robert'),		// The parameters that you want to send
	'resource' => 'users',		// The resource that you want to send to
	'method' => 'post'			// The request method that will be used
));
```

For GET method the create() method returns a querystring with the signature, public key, timestamp and the parameters to send with the request.
For all other request method the create method() returns an associative array containing the signature, public key, timestamp and the parameters.

On the REST webservice the signature will be recreated with the sent public key and parameters aswell as the current resource from the URI and the used request method. If the signatures don't match the request is denied.

## Making requests to the webservice using Guzzle

Example of a GET request

```php
$client = new GuzzleHttp\Client();

// Create a signature for the PUT request
$signature = Signature::create(array(
	'public_key' => '761146773916773',
	'private_key' => '02290a862b16082fd64a1a9799332d8e6cdc12050b1f2c19a367f6ee1e3c0de6',
	'parameters' => array('name' => 'Robert'),
	'resource' => 'users',
	'method' => 'get'
));

// Make the request
$response = $client->get('http://restwebservice.com/users/761146773916773?' . $signature);	// The signature is appened to the URL

print_r($response->json());
```

Example of a PUT request

```php
$client = new GuzzleHttp\Client();

// Create a signature for the PUT request
$signature = Signature::create(array(
	'public_key' => '761146773916773',
	'private_key' => '02290a862b16082fd64a1a9799332d8e6cdc12050b1f2c19a367f6ee1e3c0de6',
	'parameters' => array('name' => 'Robert'),
	'resource' => 'users',
	'method' => 'put'
));

// Make the request
$response = $client->put('http://restwebservice.com/users/761146773916776', [
    'body' => [
   		$signature  // The signature is added to the body
	]
]);

print_r($response->json());
```

## Configuration

In the .app/config folder are three files to change some configuration

### CORS (Cross-Origin Resource Sharing)

Location: ./app/config/cors.php

The allowed_domains array contains all the domains that may use the REST webservice. The origin of each request is checked with the $_SERVER['HTTP_ORIGIN'] variable.
If the origin is not in the allowed_domains array access will be denied.

```php
'allowed_domains' => array(
	'http://mywebsite.com',
	'http://myotherwebsite.com'
)
```

### Route settings

Location: ./app/config/routes.php

The routes config file contains URI patterns that map to a method. So when the URI is 'users/1' and the request is PUT, the method 'update()' will be called in the users route file.
When the URI is 'users/1/edit' and the request is also PUT, the method 'edit' is called in the users route file. This allows for easy control for different actions.
For example you can have different functions for POSTing data and POSTing data and returning it.

```php
'GET' => array(
	'' => 'index',
	'{resource}' => 'show'
),
'POST' => array(
	'{resource}' => 'store'
),
'PUT' => array(
	'{resource}' => 'update',
	'{resource}/edit' => 'edit'
),
'DELETE' => array(
	'{resource}' => 'destroy'
)
```