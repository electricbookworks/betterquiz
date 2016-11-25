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

	/**
	 * ResetPassword resets the user's password.
	 * @return true on success, Error class on failure.
	 */
	public static function ResetPassword($email, $code, $newPassword) {
		if (""==$code) {
			return new ErrorMessage("Sorry, your reset request code is invalid. Please try again with a new password reset request.");
		}
		$db = Database::Get();
		$stmt = $db->prepare("
			select count(*) as c from 
			user
			where
			email=? and 
			reset_request_code=? and 
			reset_request_valid>now() 
			");
		$stmt->bind_param("ss", $email, $code);
		$db->Execute($stmt);
		$c = FALSE;
		$stmt->bind_result($c);
		$stmt->fetch();
		$stmt->close();
		// If we don't find the request code or it's not valid,
		// we don't reset the password
		if (0==$c) {
			$stmt = $db->prepare(<<<EOSQL
				update user 
				set reset_request_code=null, 
				reset_request_valid=null
				where email=?
EOSQL
			);
			$stmt->bind_param("s", $email);
			$db->Execute($stmt);
			$stmt->close();
			return new ErrorMessage("Sorry, this password reset request has expired. Please try again with a new reset request.", __FILE__, __LINE__);
		}
		// We reset the password
		$stmt = $db->prepare(<<<EOSQL
			update user
			set
				reset_request_code=null,
				reset_request_valid=null,
				hash=?
			where
				email=?
EOSQL
		);
		$stmt->bind_param("ss", password_hash($newPassword, PASSWORD_DEFAULT), $email);
		$db->Execute($stmt);
		$stmt->close();
		return true;
	}

	/**
	 * GeneratePasswordResetRequest generates a password
	 * reset request for the given admin email.
	 * @return True on success, Error class on failure.
	 */
	public static function GeneratePasswordResetRequest($email) {
		$db = Database::Get();
		$stmt = $db->prepare("
			select count(*) as c from user 
			where email=?
			and is_admin=1");
		$stmt->bind_param("s", $email);
		if (!$db->Execute($stmt)) {
			die('FAILED TO select count(*) as c from user where email=' . $email . ': ' . $db->error);
		}
		$c = FALSE;
		$stmt->bind_result($c);
		$stmt->fetch();
		$stmt->close();
		if (1!=$c) {
			error_log("$email is not an admin: \$c = $c");
			// ERROR: $email is not an admin user
			return new ErrorMessage("$email is not an administrator", __FILE__, __LINE__);
		}

		$stmt = $db->prepare(<<<EOSQL
			update user set
				reset_request_code=?,
				reset_request_valid=date_add(now(), interval 1 hour)
			where
				email=?
EOSQL
		);
		$code = self::RandomString();
		$stmt->bind_param("ss", $code, $email);
		$db->Execute($stmt);
		$stmt->close();

		$url = SelfUrl::AbsoluteUrl('/admin/admin-password-reset.php', array("code"=>$code, "email"=>$email));
		error_log("Password reset url for $email: " . $url);

		mail($email, "BetterQuiz password reset request",
<<<EOTXT
We have received a password reset for the BetterQuiz admin user with your email address.

If you have requested to reset your password, please use this link: $url within the next hour.

If you have not requested a password reset, you can safely ignore this email.

With best wishes,
The BetterQuiz Team

EOTXT
		, "From: no-reply@betterquiz.com\r\n");
		return true;
	}

	/**
	 * RandomString returns a 20 character random string
	 */
	public static function RandomString() {
		return substr(md5(rand()), 0, 20);
	}

	public static function Assert($email, $password) {
		$db = Database::Get();
		$stmt = $db->prepare(<<<EOSQL
			select hash from user where email=? and is_admin=1
EOSQL
		);
		$stmt->bind_param("s", $email);
		$db->Execute($stmt);
		$h = FALSE;
		$stmt->bind_result($h);
		$stmt->fetch();
		$stmt->close();
		if (!password_verify($password, $h)) {
			new Flash("Sorry, your username and password combination is not correct. Please try again.", "error");
			return false;
		}

		return new AdminUser($email, "betterquiz");
	}
}