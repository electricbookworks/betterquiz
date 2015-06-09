<?php

/**
 * Utility class used by BQQuizList
 */
class bqQuizListItem {
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
 * BQQuizList contains a list of Quizzes.
 */
class BQQuizList {
	/**
	 * Find returns an array of bqQuizListItem's matching the provided params.
	 * The provided params are
	 * limit_from and limit_to
	 */
	public static function Find($params=array()) {
		$db = Database::Get();
		$where = "";
		if (array_key_exists("title", $params) && (""!=$params["title"])) {
			$t = $params["title"];
			$where = " where q.title like '%" . $db->Safe($t) . "%' ";
			$id = intval($t);
			if (0<$id) {
				$where .= " or q.id=$id ";
			}
		}
		$from = " from quiz q left join exam e on e.quiz_id = q.id " .
			$where . "group by q.id, q.title order by q.title, q.id";
		$limit = " limit " . $params["limit_from"] . "," . $params["limit_to"];
	
		$countStmt = $db->Prepare("select count(*) from quiz q $where");
		$db->Execute($countStmt);
		$itemsTotal = 0;
		$countStmt->bind_result($itemsTotal);
		$countStmt->fetch();
		$countStmt->Close();

		$arr = array();
		$stmt = $db->Prepare("select q.id, q.title, count(e.id) " . $from . $limit);
		$db->Execute($stmt);
		$id = 0;
		$title = "";
		$answerCount = 0;
		$stmt->bind_result($id, $title, $answerCount);
		while ($stmt->fetch()) {
			$arr[] = new bqQuizListItem($id, $title, $answerCount);
		}
		return array($itemsTotal, $arr);
	}
}