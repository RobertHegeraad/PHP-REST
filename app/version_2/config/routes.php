<?php

return array(

	/* ----------------------------------------------------------------------------------------------
	 * Default resource routing
	 * ----------------------------------------------------------------------------------------------
	 *
	 * Advised not to change
	 */

	'default' => array(
		'{resource}/' => array(
			'get' => 'index',
			'post' => 'store'
		),
		'{resource}/{id}' => array(
			'get' => 'show',
			'put' => 'update',
			'patch' => 'patch',
			'delete' => 'destroy'
		)
	),



	/* ----------------------------------------------------------------------------------------------
	 * Custom resource routing
	 * ----------------------------------------------------------------------------------------------
	 *
	 * Add resource URI's and method names
	 */

	'custom' => array(
		'users/1' => array(),
		'users/1/{id}' => array(),
		'users/{id}/deleted' => array(
			'get' => 'deleted'
		)
	)
);