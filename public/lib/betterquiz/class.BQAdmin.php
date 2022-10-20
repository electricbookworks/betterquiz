<?php

/**
 * BQAdmin is a single administrator on the BQ site.
 */
class BQAdmin {
	protected $_email;
	public function __construct($email) {
		$this->_email = $email;
	}
	/**
	 * Return the Email of the admin.
	 */
	public function Email() {
		return $this->_email;
	}

	/**
	 * Find returns an array containing the
	 * total number of BQAdmins, and an array of
	 * the selected BQAdmins. The $params
	 * parameter is an associative array supporting
	 * 'limit_from' and 'limit_to' to restrict the 
	 * items returned.
	 */
	public static function Find($params) {
		$db = Database::Get();
		$stmt = $db->Prepare(<<<EOSQL
			select count(*) from user where is_admin=1
EOSQL
		);
		$db->Execute($stmt);
		$adminCount = 0;
		$stmt->bind_result($adminCount);
		$stmt->fetch();
		$stmt->close();

		$from = $params["limit_from"];
		$to = $params["limit_to"];
		$stmt = $db->Prepare(<<<EOSQL
			select email from user
			where is_admin=1
			 order by email
			limit $from, $to
EOSQL
		);
		$db->Execute($stmt);
		$email = "";
		$stmt->bind_result($email);
		$rows = array();
		while ($stmt->fetch()) {
			$rows[] = new BQAdmin($email);
		}
		$stmt->close();
		return array($adminCount, $rows);
	}

	/**
	 * IsAdmin returns true if the given email
	 * address belongs to a site administrator,
	 * or FALSE otherwise.
	 */
	public static function IsAdmin($email) {
		$db = Database::Get();
		$stmt = $db->Prepare(<<<EOSQL
			select email from user
			where email=? and is_admin=1
EOSQL
		);
		$stmt->bind_param("s", $email);
		$db->Execute($stmt);
		$found = $stmt->fetch();
		$stmt->close();
		return $found;
	}

	/**
	 * AdminsList returns a list of BQAdmin records.
	 */
	public static function AdminsList() {
		$db = Database::Get();
		$stmt = $db->Prepare(<<<EOSQL
			select email from user where is_admin=1 order by email
EOSQL
		);
		$db->Execute($stmt);
		$email = "";
		$rows = array();
		$stmt->bind_result($email);
		while ($stmt->fetch()) {
			$rows[] = new BQAdmin($email);
		}
		$stmt->close();
		return $rows;
	}
	/**
	 * Delete deletes the BQAdmin with the given email.
	 */
	public static function Delete($email) {
		$db = Database::Get();
		$stmt = $db->Prepare(<<<EOSQL
			update user
			set is_admin=0 
			where email=?
EOSQL
		);
		$stmt->bind_param("s", $email);
		$db->Execute($stmt);
		$stmt->close();
	}
	/**
	 * Create creates a new BQAdmin with the given email.
	 */
	public static function Create($email) {
		$db = Database::Get();
		$stmt = $db->Prepare(<<<EOSQL
			insert into user (email, fullname, hash, regdate, is_admin)
			values (?, '', '', now(), 1)
			on duplicate key update is_admin=1
EOSQL
		);
		$stmt->bind_param('s', $email);
		$db->Execute($stmt);
		$stmt->close();
	}

}