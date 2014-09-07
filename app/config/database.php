<?php

/* ----------------------------------------------------------------------------------------------
 * FILE INFORMATION
 * ----------------------------------------------------------------------------------------------
 *
 * FILE: database.php
 *
 * LOCATION: ./application/config/database.php
 *
 * This file contains the settings needed to access your database.
 */



return array(



	/* ----------------------------------------------------------------------------------------------
	 * Default PDO fetch method
	 * ----------------------------------------------------------------------------------------------
	 *
	 * PDO will be used to handle all database interactions,
	 * this settings defines the default method of how the data should be retrieved from the database.
	 * 
	 * It can either be ASSOC for an associative array or OBJ for an stdclass object.
	 *
	 * The fetch method can also be altered at a query call.
	 */

	'default_fetch' => 'OBJ',



	/* ----------------------------------------------------------------------------------------------
	 * Database Connections
	 * ----------------------------------------------------------------------------------------------
	 *
	 * Different database connections for the application.
	 */

	'connections' => array(

		'mysql' => array(
			'driver'   => 'mysql',
			'host'     => 'localhost',
			'database' => 'pothuizen',
			'username' => 'root',
			'password' => ''
		)
	)
);