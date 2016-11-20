<?php

/** 
 * Database is an abstraction around the mysqli database connection, 
 * with exception throwing built in.
 */
class Database {
	var $_db;

	public function __construct($host, $user, $password, $db) {
		$this->_db = new mysqli($host, $user, $password, $db);
		if ($this->_db->connect_errno) {
			throw new RuntimeException("Failed to connect to MySQL: (" . 
				$this->_db->connect_errno . ") " . $this->_db->connect_error);
		}
	}

	/**
	 * Return the global database if the given database
	 * is invalid, otherwise return the given database.
	 */
	public static function Get($thedb=false) {
		if (!$thedb) {
			global $db;
			$thedb = $db;
		}
		return $thedb;
	}

	/**
	 * Return the Database object itself.
	 */
	public function Db() {
		return $this->_db;
	}

	/**
	 * Ensure that the given string ($p) is SQL safe.
	 */
	public function Safe($p) {
		return $this->_db->real_escape_string($p);
	}

	public function Autocommit($ac) {
		$this->_db->autocommit($ac);
	}

	/**
	 * Execute the given statement. Throw
	 * an exception if an error occurs.
	 */
	public function Execute($stmt) {
		$res = $stmt->execute();
		if (!$res) {
			throw new RuntimeException($stmt->error);
		}
		return $res;
	}

	/**
	 * Return the last insert id.
	 */
	public function LastInsertId() {
		return $this->_db->insert_id;
	}

	/**
	 * Prepare the given SQL, throwing an exception on error.
	 */
	public function Prepare($sql) {
		$stmt = $this->_db->prepare($sql);
		if (!$stmt) {
			throw new RuntimeException("Error on prepare $sql: " . $this->_db->error);
		}
		return $stmt;
	}

	/**
	 * Execute the given query, throwing an exception on error.
	 */
	public function Query($sql) {
		$res = $this->_db->query($sql);
		if (!$res) {
			throw new RuntimeException("Error on query $sql: " . $this->_db->error);
		}
		return $res;
	}
}
