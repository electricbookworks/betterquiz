<?php

/**
 * AdminUser contains details on an Admin user that has
 * logged in.
 */
class AdminUser {
	
	public function __construct($email, $issuer) {
		$this->Email = $email;
		$this->Issuer = $issuer;
	}

	/**
	 * Return the logged in AdminUser, or null
	 * if no user is logged in.
	 */
	public static function Get() {
		session_start();
		if (array_key_exists("Email", $_SESSION) && (isset($_SESSION["Email"]))) {
			return new AdminUser($_SESSION["Email"], $_SESSION["Issuer"]);
		}
		return null;
	}

	/**
	 * Save the AdminUser to the web session.
	 */
	public function Save() {
		session_start();
		$_SESSION["Email"] = $this->Email;
		$_SESSION["Issuer"] = $this->Issuer;
		session_commit();
	}

	/** 
	 * Clear the AdminUser from the web session, thereby
	 * logging the user off.
	 */
	public static function Clear() {
		session_start();
		unset($_SESSION["Email"]);
		unset($_SESSION["Issuer"]);
		session_commit();
	}

	/**
	 * Utility method that ensures that an AdminUser
	 * is logged in, or the user is forwarded to the
	 * login page.
	 */
	public static function Secure() {
		$user = self::Get();
		if (null==$user) {
			header("Location: login.php");
			die();
		}
		return $user;
	}
}