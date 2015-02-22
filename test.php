<?php

require_once("vendor/autoload.php");

// Get the products for the user
$client = new GuzzleHttp\Client();

// GET
$response = $client->get('http://rest.com/1/users?id=100&name=Robert Hegeraad', [
	// 'headers' => ['accept' => 'application/x-www-form-urlencoded'],
	'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json'],  // Als er geen content type is gezet dan staat de default niet in request content types
	'body' => ['test' => 'dsadsa']	// Body array overwrite naar x-www-form-urlencoded
]);




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




// // PUT
// $signature = Signature::create(array(
// 	'public_key' => '761146773916773',
// 	'private_key' => '02290a862b16082fd64a1a9799332d8e6cdc12050b1f2c19a367f6ee1e3c0de6',
// 	'parameters' => array('name' => 'Robert'),
// 	'resource' => 'users',
// 	'method' => 'delete'
// ));

// // Delete
// $response = $client->delete('http://restwebservice.com/users/761146773916776', [
//     'body' => [
//    		$signature
// 	]
// ]);


echo $response->getBody();

echo '<pre>';
var_dump($response->json());
echo '</pre>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	
	<button id="ajax">AJAX Request</button>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>
	
	$("#ajax").on('click', function(e) {
		$.ajax({
			url: 'http://rest.com/users/?name=Robert',
			method: 'post',
			data: {
				'name': 'Roberts'
			},
			success: function(data) {
				console.log(data);
			}
		});
	});

</script>
</body>
</html>