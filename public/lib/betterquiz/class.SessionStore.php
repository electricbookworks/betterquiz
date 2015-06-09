<?php

/**
 * SessionStore stores data for the exam that a user is completing.
 * Although called 'SessionStore', there is no guarantee 
 * that the information
 * will be stored in the session. This class therefore abstracts 
 * away the actual storage method for the site developer.
 */
class SessionStore {
	public static function Store($key, $value) {
		@session_start();
		$_SESSION[$key] = $value;
		session_commit();
	}
	public static function Clear($key) {
		@session_start();
		unset($_SESSION[$key]);
		session_commit();
	}
	public static function Get($key, $default=false) {
		@session_start();
		return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
	}
}