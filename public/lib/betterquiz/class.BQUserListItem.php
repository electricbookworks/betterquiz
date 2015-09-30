<?php

/**
 * A User in a list of users. This class is a little
 * divergent from a BQUser because it contains a count
 * of the number of exams a user has completed.
 */
class BQUserListItem {
	public function __construct($id, $fullname, $mobile, $email, $examCount) {
		$this->_id = $id;
		$this->_fullname = $fullname;
		$this->_mobile = $mobile;
		$this->_email = $email;
		$this->_examCount = $examCount;
	}
	public function Id() {
		return $this->_id;
	}
	public function Fullname() {
		return $this->_fullname;
	}
	public function Mobile() {
		return $this->_mobile;
	}
	public function Email() {
		return $this->_email;
	}
	public function ExamCount() {
		return $this->_examCount;
	}

	public static function Find($params) {
		$db = Database::Get();
		$search = " ((merge_with is null) or (0 = merge_with)) ";
		if (array_key_exists("search", $params) && (0<strlen($params['search']))) {
			$s = $db->Safe($params['search']);
			$search .= " and (fullname like '%$s%' or email like '%$s%' or mobile like '%$s%')";
		}
		$limit = " limit " . $params["limit_from"] . "," . $params["limit_to"];
	
		$countStmt = $db->Prepare("select count(*) from user where $search");
		$db->Execute($countStmt);
		$itemsTotal = 0;
		$countStmt->bind_result($itemsTotal);
		$countStmt->fetch();
		$countStmt->Close();

		$arr = array();
		$stmt = $db->Prepare($sql = <<<EOSQL
	select u.id, u.fullname, u.mobile, u.email, 
		(select count(*) from exam where user_id=u.id)
		from user u 
		where $search 
		$limit
EOSQL
		);
		$db->Execute($stmt);
		$id = 0;
		$fullname = "";
		$mobile = "";
		$email = "";
		$count = 0;
		$stmt->bind_result($id, $fullname, $mobile, $email, $count);
		while ($stmt->fetch()) {
			$arr[] = new BQUserListItem($id, $fullname, $mobile, $email, $count);
		}
		return array($itemsTotal, $arr);		
	}
}