<?php

/**
 * BQUserForgot handles forgotten passwords.
 * The class will assign a new password for a user, permitting the user to 
 * login with the new password during the timed login period.
 * If the user logs in with the new password, the user's password is updated
 * to the new password.
 */
class BQUserForgot {
	/**
	 * NewPassword generates a new password for the user with the given
	 * emailMobile.
	 * It populates the appropriate user_forgot table with the 
	 * password, and an expiry time.
	 * It returns the new password, or FALSE if the user was not found.
	 */
	public static function NewPassword($db=false, $emailMobile) {
		$db = Database::Get($db);
		$user = BQUser::LoadUserByEmailOrMobile($db, $emailMobile);
		if (!$user) {
			return FALSE;
		}
		$newPassword = self::generatePassword();
		$hash = password_hash($newPassword, PASSWORD_DEFAULT);
		$stmt = $db->Prepare(<<<EOSQL
			insert into user_forgot
			(uid, email, mobile, hash, expiry)
			values
			(?,?,?,?,date_add(now(), interval 1 day))
EOSQL
		);
		$stmt->bind_param("isss", $user->Id(), $user->Email(), $user->Mobile(), $hash);
		$db->Execute($stmt);
		$stmt->Close();
		return $newPassword;
	}

	/**
	 * VerifyPassword checks the given password for the 
	 * given user. If the password matches the stored
	 * hash, the user's password is updated to the 
	 * password that has just been verified.
	 */
	public static function VerifyPassword($db=false, $emailMobile, $password) {
		$db = Database::Get($db);
		$fld = Utils::IsEmail($emailMobile) ? "email" : "mobile";
		$stmt = $db->Prepare( <<<EOSQL
			select uid, hash 
			from user_forgot where $fld = ?
			and
			expiry >= now()
			order by expiry desc
			limit 0, 1
EOSQL
		);
		$stmt->bind_param("s", $emailMobile);
		$id = null;
		$hash = null;
		$stmt->bind_result($id, $hash);
		$db->Execute($stmt);
		$stmt->fetch();
		$stmt->Close();

		// BQUserForgot always returns a bad-password, because to
		// get to calling BQUserForgot, we've already confirmed that
		// the user exists. So 'no-user' cannot be correct at this point.
		if ((null==$id) || (!password_verify($password, $hash))) {
			return array("bad-password", false);
		}
		// We've successfully logged in with the new password,
		// so we change the user's password and delete all user-related
		// forgot records
		BQUser::UpdateHash($db, $id, $hash);
		$stmt = $db->Prepare(<<<EOSQL
			delete from user_forgot where uid=?
EOSQL
		);
		$stmt->bind_param("i", $id);
		$db->Execute($stmt);
		$stmt->Close();

		return array($id, true);
	}

	/**
	 * EmailPassword emails the new password to the user with the
	 * given userId.
	 */
	public static function EmailPassword($email, $new) {
		$user = BQUser::LoadUserByEmailOrMobile(false, $email);
		if (!$user) {
			return FALSE;
		}
		$fullname = $user->Fullname();
		$msg = <<<EOMAIL
Dear $fullname,

We received a request for a new password on Bettercare.

We've set a new password for you: $new

(Your old password still works.)

You can login using this new password during the next hour.

All the best,
The Bettercare Team
EOMAIL
		;
		$headers = 'From: no-reply@bettercare.co.za' . "\r\n" .
		    'Reply-To: no-reply@bettercare.co.za' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		mail($email, "Bettercare: Password Reset", $msg, $headers);
		return true;
	}

	/**
	 * SMSPassword SMS's the new password to the user with the
	 * given mobile number.
	 */
	public static function SMSPassword($uid, $mobile, $new) {
		$msg = <<<EOSMS
We've assigned you a new password on Bettercare: $new 
You can login with this password during the next hour.
EOSMS
		;
		$sms = new BulkSMS();
		$sms->Send($mobile, $msg);
		return true;
	}

	/**
	 * generatePassword returns a random password that is n characters long
	 */
	public static function generatePassword($n=8) {
		$arr = [];
		$from = "abcdefghjklmnpqrstwxyz23456789";
		srand(microtime(true));
		for ($i=0; $i<$n; $i++) {
			$p = rand(0, strlen($from));
			$arr[] = substr($from, $p, 1);
		}
		return implode("", $arr);
	}
}