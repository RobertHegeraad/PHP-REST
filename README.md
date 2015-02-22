PHP Basic REST setup
=====

# Resources

Resources are placed inside the ./app/version_1/resources folder. A resource must extend the Resource class.

```php
class Users extends Resource {

	/*
	 * GET /users
	 */
	public function get() { ... }

	/*
	 * GET /users/1
	 */
	public function detail() { ... }

	/*
	 * POST /users
	 */
	public function post() { ... }

	/*
	 * PUT /users/1
	 */
	public function put() { ... }

	/*
	 * PATCH /users/1
	 */
	public function patch() { ... }

	/*
	 * DELETE /users/1
	 */
	public function delete() { ... }
}
```

Note: Methods for OPTIONS and HEAD request are part of the Request class and can't be overridden.

Inside a resource class you can use it's Request and Response objects.

### Request

The Request object contains information about the request like input and the URI

```php
$this->request->url->id 	// Get the ID from the URI
$this->request->input 		// Get the request body
$this->request->ajax 		// Whether the request was made via AJAX
```

Example:

```php
/*
 * GET /users/1
 */
public function detail() {
	// Select user by ID
	$user = Database::select('SELECT * FROM users WHERE id = ?', array(
		$this->request->url->id
	));
}
```

### Response

The Response object is used to return data. You can return a Body or just a Status code.

```php
$this->response->body(200, $users); 	// Return the users
$this->reponse->status(404); 			// Returns a 404 with the corresponding message
```

Example:

```php
/*
 * GET /users/1
 */
public function get() {
	// Select users
	$users = Database::select('SELECT * FROM users);

	// Return the users
	$this->response->body(200, $users);
}
```

### Options

In the app/version_1/config/resources.php file you can set the available request methods for a resource. In the following example the URI users/ only allows GET, POST and OPTIONS requests. The URI users/1 allows GET, PUT, DELETE and OPTIONS requests

```php
'users' => array(
	'/' => array('GET', 'POST', 'OPTIONS'),
	'/{id}' => array('GET', 'PUT', 'DELETE', 'OPTIONS')
),
```

If the above configuration does not exist the only available request method is GET.

# Versions

You can easily add different versions for the API. In the ./app folder simply add a folder with the version number and prefix it with 'version_'. Next copy over the config and resources folders from the previous version. Now the settings from the config folder are only active for the version folder it is in.

A version can be passed in the URL in the first segment.

```html
http://webservice.com/2/users	// 2 is the version number
```

This will search for the users resource in the ./app/version_2/resources folder.

If the version number is left out of the URL it defaults to 1.

```html
http://webservice.com/users	// 1 is the version number
```

# Todo list

* Resource links
* Pagination
* Custom methods
* Authentication
* Allow Origin