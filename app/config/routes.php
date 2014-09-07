<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Holds the routes and the corresponding function names
	|--------------------------------------------------------------------------
	|
	|	Verb 		Path 						Action 		Route Name
	|	------------------------------------------------------------------
	|	GET 		/resource 					index 		resource.index
	|	GET 		/resource/create 			create 		resource.create
	|	POST 		/resource 					store 		resource.store
	|	GET 		/resource/{resource} 		show 		resource.show
	|	GET 		/resource/{resource}/edit 	edit 		resource.edit
	|	PUT 		/resource/{resource} 		update 		resource.update
	|	DELETE 		/resource/{resource} 		destroy 	resource.destroy
	*/

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
);