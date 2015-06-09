<?php

/**
 * Utility class used in BQExamList
 */
class bqExamListItem {
	public function __construct($qid, $title, $answerCount) {
		$this->_id = $qid;
		$this->_title = $title;
		$this->_answerCount = $answerCount;
	}
	public function Id() {
		return $this->_id;
	}
	public function Title() {
		return $this->_title;
	}
	public function AnswerCount() {
		return $this->_answerCount;
	}
}

/**
 * BQExamList provides a list of BQExams completed.
 */
class BQExamList {
	/**
	 * Find returns an array of bqExamListItem's matching the provided params.
	 * The provided params are
	 * limit_from and limit_to
	 */
	public static function Find($params=array()) {
		$db = Database::Get();
		$from = " from quiz q left join answer a on a.quiz_id = q.id ";
		$limit = " limit " . $params["limit_from"] . "," . $params["limit_to"];
	
		$countStmt = $db->Prepare("select count(*) from " . $from);
		$db->Execute($countStmt);
		$itemsTotal = 0;
		$countStmt->bind_result($itemsTotal);
		$countStmt->fetch();
		$countStmt->Close();

		$arr = array();
		$stmt = $db->Prepare("select q.id, q.title, count(a.id) " . $from . $limit);
		$db->Execute($stmt);
		$id = 0;
		$title = "";
		$answerCount = 0;
		$stmt->bind_result($id, $title, $answerCount);
		for ($stmt->fetch()) {
			$arr[] = new bqExamListItem($id, $title, $answerCount);
		}
		return array($itemsTotal, $arr);
	}

}