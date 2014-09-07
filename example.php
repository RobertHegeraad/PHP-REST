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

// var_dump($signature);

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

var_dump($signature);

$response = $client->delete('http://restwebservice.com/users/761146773916776', [
    'body' => [
   		$signature
	]
]);

// $response = $client->put('http://restwebservice.com/users/1', array(
// 	'Content-Length' => strlen($signature),
// 	'Transfer-Encoding' => 'chunked'
// ), array($signature));

// $r = curl::put('http://restwebservice.com/users/1', array('dsad' => 'dsa'));

echo '<pre>';
var_dump($response->json());
echo '</pre>';


// http://jcalcote.wordpress.com/2008/10/16/put-or-post-the-rest-of-the-story/
// Headers goed setten? content-length moet precies die length zijn van de body
// via cURL proberen en dan custom cURL met guzzle


?>
<!doctype html>
<html>
<head>
	<title>REST</title>
</head>
<body>

	<input class="btn" type="submit" value="Get Users"/>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>

	// $(document).on('click', '.btn', function()
	// {
	// 	$.ajax({
	// 		type: 'GET',
	// 		url: 'http://localhost:8080/Work/REST/users',
	// 		data: {
	// 			hash: '<?php echo $hash; ?>',
	// 			id: '<?php echo $user_id; ?>',
	// 			timestamp: '<?php echo time(); ?>'
	// 		},
	// 		success: function(r) {
	// 			console.log(r);
	// 		}
	// 	})
	// });
	
</script>
</body>
</html>