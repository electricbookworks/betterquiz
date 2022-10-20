<?php

include_once("class.BQExam.php");
include_once("class.BQAnswer.php");

/**
 * BQUser is a user on the BetterCare Quiz system who 
 * answers quizzes.
 */
class BQUser {
	var $_id;
	var $_fullname;
	var $_email;
	var $_mobile;
	var $_regdate;
	var $_mergeId;	/// Id of the user the this user is merged with.
	var $_hash;

	/** 
	 * @brief Default constructor.
	 */
	public function __construct($id, $fullname, $email, $mobile, $password, $regdate=false, $mergeId=false) {
		$this->_id = $id;
		$this->_fullname = $fullname;
		$this->_email = $email;
		$this->_mobile = Utils::FilterMobile($mobile);
		$this->_regdate = $regdate;
		$this->_mergeId = $mergeId;
		if ($password) {
			$this->_hash = password_hash($password, PASSWORD_DEFAULT);
		} else {
			$this->_hash = FALSE;
		}
	}

	public function Id() {
		return $this->_id;
	}
	public function Fullname() {
		return $this->_fullname;
	}
	public function SetFullname($fullname) {
		$this->_fullname = $fullname;
	}
	public function Email() {
		return $this->_email;
	}
	/**
	 * EmailOrMobile returns the user's email address, or
	 * if they haven't set an Email address, their mobile
	 * number.
	 */
	public function EmailOrMobile() {
		if ($this->Email() && 0<strlen($this->Email())) {
			return $this->Email();
		}
		return $this->Mobile();
	}
	public function SetEmail($email) {
		$this->_email = $email;
	}
	public function Mobile() {
		return $this->_mobile;
	}
	public function SetMobile($mobile) {
		$this->_mobile = Utils::FilterMobile($mobile);
	}
	public function SetPassword($password) {
		$this->_hash = password_hash($password, PASSWORD_DEFAULT);
	}
	/**
	 * @brief Return the user that this user is merged with, 
	 * or this User if this is not a merged user.
	 */
	public function GetActualUser() {
		if (null==$this->_mergeId) {
			return $this;
		}
		// Recurse at this point to ensure that even multiple merges will work.
		return BQUser::LoadUserById(null, $this->_mergeId)->GetActualUser();
	}
	/**
	 * @brief Return TRUE if this user has been merged with another user.
	 */
	public function IsMerged() {
		return (null != $this->_mergeId);
	}

	public static function LoadUserById($db, $id) {
		if (0==$id) {
			return new BQUser(0, "","","",false,false);
		}
		$db = Database::Get($db);
		$stmt = $db->Prepare( <<<EOSQL
			select fullname, email, mobile, hash, regdate, merge_with
			from user where id = ?
EOSQL
			);
		$stmt->bind_param("i", $id);
		$db->Execute($stmt);

		$fullname = null;
		$email = null;
		$mobile = null;
		$hash = null;
		$regdate = null;
		$mergeId = null;

		$stmt->bind_result($fullname, $email, $mobile, $hash, $regdate, $mergeId);
		$stmt->fetch();
		$stmt->Close();
		$user =  new BQUser($id, $fullname, $email, $mobile, false, $regdate, $mergeId);
		$user->_hash = $hash;
		return $user;
	}

	public function SaveToDatabase($db) {
		$db = Database::Get($db);
		if (!$this->_mergeId || intval($this->_mergeId)==0) {
			$this->_mergeId = null;
		}
		if (0==$this->_id) {
			$stmt = $db->Prepare( <<<EOSQL
				insert into user
				(fullname, email, mobile, hash, regdate, merge_with)
			 	values (?,?,?,?,now(),?)
EOSQL
			);
			error_log('_mergeId = ' . $this->_mergeId . ", type=". gettype($this->_mergeId));
			$stmt->bind_param("ssssi",
				$this->_fullname, $this->_email, $this->_mobile, $this->_hash, $this->_mergeId);
			$db->Execute($stmt);
			$this->_id = $db->LastInsertId();
		} else {
			if ($this->_hash) {
				$stmt = $db->Prepare( <<<EOSQL
					update user set 
					fullname=?, email=?, mobile=?, hash=?, merge_with=? 
					where id=?
EOSQL
				);
				$stmt->bind_param("ssssii",
					$this->_fullname,
					$this->_email,
					$this->_mobile, 
					$this->_hash,
					$this->_mergeId,
					$this->_id);
			} else {
				$stmt = $db->Prepare(<<<EOSQL
					update user
					set fullname=?, email=?, mobile=?, merge_with=?
					where
					  id=?
EOSQL
				);
				$stmt->bind_param("sssii",
					$this->_fullname,
					$this->_email,
					$this->_mobile,
					$this->_mergeId,
					$this->_id);
			}
			$db->Execute($stmt);
			$stmt->Close();
		}
	}

	public static function VerifyPassword($db=false, $emailMobile, $password) {
		$db = Database::Get($db);
		$mobile = Utils::FilterMobile($emailMobile);	
		$fld =  ($mobile) ? "mobile" : "email";
		if ($mobile) {
			$emailMobile = $mobile;
		}
		$stmt = $db->Prepare("select id, hash from user where $fld = ?");
		$stmt->bind_param("s", $emailMobile);
		$id = null;
		$hash = null;
		$stmt->bind_result($id, $hash);
		$db->Execute($stmt);
		$foundUser = $stmt->fetch();
		$stmt->close();

		if (!$foundUser) {
			return array("no-user", false);
		}
		if (!password_verify($password, $hash)) {
			return BQUserForgot::VerifyPassword($db, $emailMobile, $password);
		}
		return array($id, true);
	}

	public static function UpdateHash($db, $uid, $hash) {
		$db = Database::Get($db);
		$stmt = $db->Prepare(<<<EOSQL
			update user set hash=? where id = ?
EOSQL
		);
		$stmt->bind_param("si", $hash, $uid);
		$db->Execute($stmt);
		$stmt->Close();
	}
	/**
	 * Return TRUE if a user exists with the given email
	 * address.
	 */
	public static function UserEmailExists($db=false, $email) {
		return self::checkForUser($db, "email", $email);
	}
	/**
	 * Return TRUE if a user exists with the given mobile
	 * number.
	 */
	public static function UserMobileExists($db=false, $mobile) {
		return self::checkForUser($db, "mobile", Utils::FilterMobile($mobile));
	}
	/**
	 * Return the ID of the user with the given email.
	 */
	public static function UserIdForEmail($db, $email) {
		return self::loadUserIdFor($db, "email", $email);
	}
	/**
	 * Return the ID of the user with the given mobile number.
	 */
	public static function UserIdForMobile($db, $mobile) {
		return self::loadUserIdFor($db, "mobile", Utils::FilterMobile($mobile));
	}

	protected static function checkForUser($db=false, $fld, $value) {
		$db = Database::Get($db);
		$stmt = $db->Prepare("select count(*) from user where $fld=?");
		$stmt->bind_param("s", $value);
		$db->Execute($stmt);
		$count = 0;
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->Close();
		return (0<$count);
	}

	/**
	 * Load a User with the given email or mobile
	 * number. Return BQUser or FALSE if no such
	 * user exists.
	 */
	public static function LoadUserByEmailOrMobile($db=false, $emailMobile) {
		if (Utils::IsEmail($emailMobile)) {
			$uid = self::UserIdForEmail($db, $emailMobile);
		} else {
			$uid = self::UserIdForMobile($db, $emailMobile);
		}
		if (!$uid) {
			return FALSE;
		}
		return self::LoadUserById($db, $uid);
	}

	protected static function loadUserIdFor($db = false, $fld, $value) {
		if (!$value) {
			return false;
		}
		$db = Database::Get($db);
		$stmt = $db->Prepare("select id from user where $fld=?");
		$stmt->bind_param("s", $value);
		$db->Execute($stmt);
		$id = 0;
		$stmt->bind_result($id);
		$stmt->fetch();
		$stmt->Close();
		return $id;
	}

	/** 
	 * StoreSession stores the UserID in the web
	 * session.
	 */
	public static function StoreSession($userId) {
		SessionStore::Store("user_id", $userId);
	}

	/**
	 * ClearSession removes the User ID from the web
	 * session.
	 */
	public static function ClearSession() {
		SessionStore::Clear("user_id");
	}

	/**
	 * GetSession returns the User ID if the user
	 * is logged in.
	 */
	public static function GetSession() {
		return SessionStore::Get("user_id");
	}

	/**
	 * Load all the exams completed by a user.
	 * @return []BQExam Array of BQExam objects for each exam
	 * the user has completed.
	 */
	public static function LoadExamsForUser($userId) {
		$db = Database::Get();
		$stmt = $db->Prepare("select id from exam where user_id=? order by startdate");
		$stmt->bind_param("i", $userId);
		$db->Execute($stmt);
		$r = array();

		$id = 0;

		$stmt->bind_result($id);
		while ($stmt->fetch()) {
			$r[] = $id;
		}
		$stmt->Close();

		$e = array();
		foreach ($r as $examId) {
			$e[] = BQExam::LoadExamById($db, $examId);
		}
		return $e;
	}	

	/**
	 * @brief MergeUsers will merge two user accounts, so that all records
	 * from $mergeFrom become records for $mergeTo, and the user can
	 * only login as $mergeTo henceforward.
	 *
	 * This functionality should be used with some caution, as there is essentially
	 * no way to rollback after merging - because the exam records are updated.
	 */
	public static function MergeUsers($mergeFrom, $mergeTo) {
		$db = Database::Get();
		$stmt = $db->Prepare(<<<EOSQL
			update user set merge_with=? where id=?
EOSQL
		);
		$stmt->bind_param("ii", $mergeTo, $mergeFrom);
		$db->Execute($stmt);
		$stmt->Close();

		$stmt = $db->Prepare(<<<EOSQL
			update exam set user_id=? where user_id=?
EOSQL
		);
		$stmt->bind_param("ii", $mergeTo, $mergeFrom);
		$db->Execute($stmt);
		$stmt->Close();
	}
}