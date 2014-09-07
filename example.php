<?php

require_once("vendor/autoload.php");

// Get the products for the user
$client = new GuzzleHttp\Client();

// // GET
// $signature = Signature::create(array(
// 	'public_key' => '761146773916773',
// 	'private_key' => '02290a862b16082fd64a1a9799332d8e6cdc12050b1f2c19a367f6ee1e3c0de6',
// 	'parameters' => array('name' => 'Robert'),
// 	'resource' => 'users',
// 	'method' => 'get'
// ));

// $response = $client->get('http://restwebservice.com/users/1?' . $signature);




// // GET by ID
// $signature = Signature::create(array(
// 	'public_key' => '761146773916773',
// 	'private_key' => '02290a862b16082fd64a1a9799332d8e6cdc12050b1f2c19a367f6ee1e3c0de6',
// 	'parameters' => array('name' => 'Robert'),
// 	'resource' => 'users',
// 	'method' => 'get'
// ));

// $response = $client->get('http://restwebservice.com/users/1?' . $signature);




// // POST
// $signature = Signature::create(array(
// 	'public_key' => '761146773916773',
// 	'private_key' => '02290a862b16082fd64a1a9799332d8e6cdc12050b1f2c19a367f6ee1e3c0de6',
// 	'parameters' => array('name' => 'Robert'),
// 	'resource' => 'users',
// 	'method' => 'post'
// ));

// $response = $client->post('http://restwebservice.com/users/1', [
//     'body' => [
//     	'public' => '761146773916773',
//     	'name' => 'Robert',
//     	'signature' => $signature,
//     	'stamp' => time()
// 	]
// ]);




// PUT
$signature = Signature::create(array(
	'public_key' => '761146773916773',
	'private_key' => '02290a862b16082fd64a1a9799332d8e6cdc12050b1f2c19a367f6ee1e3c0de6',
	'parameters' => array('name' => 'Robert'),
	'resource' => 'users',
	'method' => 'delete'
));

$response = $client->delete('http://restwebservice.com/users/761146773916776', [
    'body' => [
   		$signature
	]
]);

echo '<pre>';
var_dump($response->json());
echo '</pre>';

?>