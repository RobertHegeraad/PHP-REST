<?php

/* ----------------------------------------------------------------------------------------------
 * FILE INFORMATION
 * ----------------------------------------------------------------------------------------------
 *
 * FILE: database.php
 *
 * LOCATION: ./system/libraries/database.php
 *
 * Contains database functionalities, using PDO 
 */



class Database {
	
	/**
	 * @var handle The database connection
	 */
	public static $db;

    /**
     * @var array Array containing all the PDO fetch methods
     */
    protected static $fetch_methods = array(
        'ASSOC'     => PDO::FETCH_ASSOC,
        'OBJ'       => PDO::FETCH_OBJ,
        'LAZY'      => PDO::FETCH_LAZY,
        'NAMED'     => PDO::FETCH_NAMED,
        'NUM'       => PDO::FETCH_NUM,
        'BOTH'      => PDO::FETCH_BOTH,
        'COLUMN'    => PDO::FETCH_COLUMN,
        'UNIQUE'    => PDO::FETCH_UNIQUE,
        'KEY_PAIR'  => PDO::FETCH_KEY_PAIR
    );


	/** -----------------------------------------------------------------------------------------------
	 * Connect to the database using PDO
	 *
	 * @param  array  $info  The database connection information
	 */
	public static function connect($info) {
		static::$db = new PDO($info['driver'] . ':host=' . $info['host'] . '; dbname=' . $info['database'], $info['username'], $info['password']);
		static::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}


	/** -----------------------------------------------------------------------------------------------
	 * Run a select query
	 *
	 * @param  string The query string
	 * @param  array  The bindings for the query
	 * @param  string The PDO fetch method that will be used to fetch the data
	 */
	public static function select($query, $bindings = array(), $fetch_method = FALSE) {
        
        // If the $fetch_method parameter is set, use it, else use the default fetch method from the database config array
        if($fetch_method === FALSE) {
            $fetch_method = Config::get('database', 'default_fetch');
        }

        $stmt = self::$db->prepare($query);
        $stmt->execute($bindings);

        $rowCount = $stmt->rowCount();


        // If zero rows are returned return an empty array
        if($rowCount == 0) {
            return array();
        }
        // If one row is returned return a single array/object
        else if($rowCount == 1) {
            return $stmt->fetch(self::$fetch_methods[$fetch_method]);
        }
        // If multiple rows are returned return an array containing multiple arrays/objects
        else {
            return $stmt->fetchAll(self::$fetch_methods[$fetch_method]);
        }
	}


    /** -----------------------------------------------------------------------------------------------
     * INSERT a row in the database and retrieve the last inserted ID
     * (Last insterted ID only works for auto incremented ID's)
     *
     * @param array
     * @param array
     * @return int The last inserted ID
     */
    public static function insert($query, $bindings = array()) {
        $stmt = self::$db->prepare($query);

        $stmt->execute($bindings);

        return self::$db->lastInsertId();
    }


    /** -----------------------------------------------------------------------------------------------
     * UPDATE a row in the database
     *
     * @param array
     * @param array
     */
    public static function update($query, $bindings = array()) {
        $stmt = self::$db->prepare($query);

        $stmt->execute($bindings);
    }


    /** -----------------------------------------------------------------------------------------------
     * Remove a row from the database
     *
     * @param $query
     * @param array
     */
    public static function remove($query, $bindings = array()) {
        $stmt = self::$db->prepare($query);

        $stmt->execute($bindings);
    }


    /** -----------------------------------------------------------------------------------------------
     * Start a transaction
     */
    public static function beginTransaction() {
        self::$db->beginTransaction();
    }


    /** -----------------------------------------------------------------------------------------------
     * Rollback a transaction
     */
    public static function rollback() {
        self::$db->rollback();
    }


    /** -----------------------------------------------------------------------------------------------
     * Commit a transaction
     */
    public static function commit() {
        self::$db->commit();
    }
}