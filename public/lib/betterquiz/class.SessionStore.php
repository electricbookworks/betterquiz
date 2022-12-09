<?php

/**
 * SessionStore stores data for the exam that a user is completing.
 * Although called 'SessionStore', there is no guarantee 
 * that the information
 * will be stored in the session. This class therefore abstracts 
 * away the actual storage method for the site developer.
 */
class SessionStore {
	public static function Start() {
		if (!@session_start()) {
			die('Session failed to start');
		}
	}
	public static function Commit() {
		session_commit();
	}
	public static function Store($key, $value) {
		self::Start();
		$_SESSION[$key] = $value;
		self::Commit();
	}
	public static function Clear($key) {
		self::Start();
		unset($_SESSION[$key]);
		self::Commit();
	}
	public static function Get($key, $default=false) {
		self::Start();
		return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
	}
}