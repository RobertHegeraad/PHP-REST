<?php

class Users {

	/*
	|--------------------------------------------------------------------------
	| Resourceful Controller
	|--------------------------------------------------------------------------
	|
	|	Verb 		Path 						Action 		Route Name
	|	------------------------------------------------------------------
	|	GET 		/resource 					index 		resource.index
	|	GET 		/resource/create 			create 		resource.create
	|	POST 		/resource 					store 		resource.store
	|	GET 		/resource/{resource} 		show 		resource.show
	|	GET 		/resource/{resource}/edit 	edit 		resource.edit
	|	PUT/PATCH 	/resource/{resource} 		update 		resource.update
	|	DELETE 		/resource/{resource} 		destroy 	resource.destroy
	*/

	/*
	 * GET
	 *
	 * @param array Request array containing the input that was sent with the request and the URI segments
	 * - input 		- All the input parameters that were sent with the body or the querystring
	 * - resource 	- The used resource, in this case 'users'
	 * - parameter 	- The parameter that was sent in the URI
	 */
	public function index($request)
	{
		// Get all the users
		$users = Database::select('SELECT * FROM users');

		return Response::json(array('users' => $users), 200);
	}

	/*
	 * Get by ID
	 */
	public function show($request)
	{
		// Get a single user by the provided ID
		$user = Database::select("SELECT * FROM users WHERE user_id = ?", array($request->parameter), 'ASSOC');

		return Response::json(array('user' => $user), 200);
	}

	/*
	 * POST
	 */
	public function store($request)
	{
		// Insert a new user
		$last_inserted_id = Database::insert("INSERT INTO `pothuizen`.`users` (`fb_user_id`, `fb_first_name`, `fb_last_name`, `fb_email`, `remember_token`, `updated_at`, `deleted`) VALUES ('761146773916776', 'Danny', 'Hegeraad', 'dannyhegeraad@gmail.com', '0', '2014-09-07', '0')");

		return Response::json(array('status' => 200, 'last_inserted_id' => $last_inserted_id), 200);
	}

	/*
	 * PUT
	 */
	public function update($request)
	{
		// Update a row
		Database::update("UPDATE users SET fb_first_name='Robert' WHERE fb_first_name = 'Roobert'", array(), 'ASSOC');

		return Response::json(array('status' => '200', 'request' => $request), 200);
	}

	/*
	 * PUT and return
	 */
	public function edit($request)
	{
		// Update a row with the sent input
		Database::update("UPDATE users SET fb_first_name=? WHERE fb_first_name = 'Robert'", array($request->input['name']), 'ASSOC');
		
		// Select a row with the parameter that was sent in the URI
		$user = Database::select("SELECT * FROM users WHERE fb_user_id = ?", array($request->parameter), 'ASSOC');

		return Response::json(array('user' => $user, 'request' => $request->input['name']), 200);
	}

	/*
	 * DELETE
	 */
	public function destroy($request)
	{
		// Remove a row with the parameter that was sent in the URI
		Database::remove("DELETE FROM users WHERE fb_user_id = ?", array($request->parameter));

		return Response::json(array('users' => 'DELETE', 'request' => $request), 200);
	}
}