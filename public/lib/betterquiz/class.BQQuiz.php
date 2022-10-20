<?php
include_once("class.BQF.php");
include_once("class.BQQuestion.php");
include_once("class.ParserTokenReceiver.php");
include_once("class.StringStreamEmitter.php");

/**
 * A Quiz.
 */
class BQQuiz {
	var $_id;
	var $_meta;
	var $_questions;

	public function __construct() {
		$this->_id = 0;
		$this->_meta = array();
		$this->_questions = array();
	}

	/**
	 * Add a meta-data key-value pair to the Quiz.
	 */
	public function AddMeta($k, $v) {
		$this->_meta[strtolower(bqf_trim($k))] = bqf_trim($v);
	}

	/**
	 * BQFString outputs the Quiz in BQF format.
	 */
	public function BQFString() {
		$out = array();
		foreach ($this->_meta as $k=>$v) {
			$out[] = BQF::WhiteString("$k: $v");
		}
		$out[] = "\n";
		foreach ($this->_questions as $q) {
			$out[] = $q->BQFString();
		}
		return implode("", $out);
	}

	/** 
	 * Add a Question to the Quiz.
	 */
	public function AddQuestion($q) {
		$question = new BQQuestion(0, $q, count($this->_questions));
		array_push($this->_questions, $question);
		return $question;
	}

	/**
	 * Return an associative map of the metadata for
	 * the quiz.
	 */
	public function Meta() {
		return $this->_meta;
	}

	/**
	 * Return the title for the quiz, taken from the meta-data
	 * `title` element, or constructed from the 
	 * current time.
	 */
	public function Title() {
		if (array_key_exists("title", $this->_meta)) {
			$title = $this->_meta["title"];
		} else {
			$title = "New Quiz " . date('m/d/Y h:i:s a', time());
			$this->_meta["title"] = $title;
		}
		return $title;
	}

	public function Questions() {
		return $this->_questions;
	}

	public function Id() {
		return $this->_id;
	}

	/**
	 * Parse parses a BQF file, and returns a new 
	 * BQQuiz for the quiz defined in the BQF file.
	 * @param $filename string The filename for the input.
	 * This is used solely for reporting purposes.
	 * @param $data string The BQF as a string.
	 */
	public static function Parse($filename, $data) {
		$quiz = new BQQuiz();
		$receiver = new ParserTokenReceiver($quiz);
		$stream = new StringStreamEmitter($filename, $data, $receiver);
		$stream->Tokenize();
		return $quiz;
	}

	/**
	 * ParseJSON parses a JSON formatted Quiz, and
	 * returns a new BQQuiz.
	 * Although BQF is a much preferable format for
	 * quizzes, the JSON format is also supported, largely
	 * for testing purposes.
	 */
	public static function ParseJSON($js) {
		$quiz = new BQQuiz();
		$js = json_decode($js);		
		if ((FALSE===$js) || (null===$js)) {
			throw new Exception("Failed to parse JSON: " . json_last_error_msg());
		}
		foreach ($js->meta as $k=>$v) {
			$quiz->AddMeta($k, $v);
		}
		foreach ($js->questions as $q) {
			$question = $quiz->AddQuestion($q->question);
			foreach ($q->options as $o) {
				$question->AddOption($o->correct, $o->option);
			}
		}
		return $quiz;
	}

	/**
	 * Return TRUE if one quiz equals another, false otherwise.
	 */
	public function Equals($that) {
		if (count($this->_meta) != count($that->_meta)) {
			return false;
		}
		foreach ($this->_meta as $k=>$v) {
			if ($v != $that->_meta[$k]) {
				return false;
			}
		}
		if (count($this->Questions() != count($that->Questions()))) {
			return false;
		}
		for ($i=0; $i<count($this->Questions()); $i++) {
			if (!$this->Questions()[$i]->Equals($that->Questions()[$i])) {
				return false;
			}
		}
		return true;
	}


	public function SaveToDatabase($db) {
		// Turn off autocommit so that we have a transaction here
		$db->Autocommit(false);
		$db->Db()->begin_transaction();
		try {
			$quizId = $this->_id;
			if (0==$this->_id) {
				$stmt = $db->Prepare("insert into quiz (title) values (?)");
				$title = $this->Title();
				$stmt->bind_param("s", $title);
				$db->Execute($stmt);
				$this->_id = $db->LastInsertId();
				$quizId = $this->_id;
			} else {
				$stmt = $db->Prepare("update quiz set title=? where id=?");
				$quizTitle = $this->_meta["title"];
				$stmt->bind_param("si", $quizTitle, $quizId);
				$db->Execute($stmt);
			}

			// Inefficient, but we shouldn't be uploading so many quizzes that this will
			// be an issue
			$db->Query("delete from quiz_meta where quiz_id=" . intval($quizId));

			$stmt = $db->Prepare("insert into quiz_meta(quiz_id, meta_key, meta_value) values (?,?,?)");
			foreach ($this->_meta as $k=>$v) {
				$stmt->bind_param("iss", $quizId, $k, $v);
				$db->Execute($stmt);
			}

			if (0==count($this->_questions)) {
				$db->Query("delete from question where quiz_id=" . intval($quizId));
			} else {
				$ids = array();
				for ($i=0; $i<count($this->_questions); $i++) {
					$this->_questions[$i]->SaveToDatabase($db, $this, $i);
					$ids[] = intval($this->_questions[$i]->Id());
				}
				$db->Query("delete from question where quiz_id=" . intval($this->_id) . " and not " .
					"id in (" . implode(",", $ids) . ")");
			}
			$db->Db()->commit();
		} catch (RuntimeException $e) {
			$db->Db()->rollback();
			throw $e;
		}
	}

	public static function LoadFromDatabase($db, $quizId) {
		$db = Database::Get($db);
		$quiz = new BQQuiz();
		$quiz->_id = $quizId;

		$stmt = $db->Prepare("select meta_key, meta_value from quiz_meta where " .
			"quiz_id=?");
		$stmt->bind_param("i", $quizId);
		$db->Execute($stmt);

		$key = NULL;
		$value = NULL;

		$stmt->bind_result($key, $value);

		while ($stmt->fetch()) {
			$quiz->AddMeta($key, $value);
		}

		$quiz->_questions = BQQuestion::LoadQuestionsForQuiz($db, $quizId);

		return $quiz;
	}	

	/**
	 * FindFirstQuestionId is a convenience method to find the first question id
	 * of a quiz without loading the full BQQuiz class.
	 * @return int or bool The quiz ID or FALSE if no first question is found.
	 */
	public static function FindFirstQuestionId($db, $quizId) {
		$stmt = $db->Prepare(<<<EOSQL
			select id from question 
			where quiz_id=? 
			order by question_number asc limit 0,1
EOSQL
		);
		$stmt->bind_param("i", $quizId);
		$db->Execute($stmt);
		$id = false;
		$stmt->bind_result($id);
		if (!$stmt->fetch()) {
			return false;
		}
		$stmt->Close();
		return $id;
	}

	/**
	 * DoesQuizExist returns TRUE if a quiz exists with the given
	 * quizId, false otherwise.
	 */
	public static function DoesQuizExist($quizId) {
		$db = Database::Get();
		$stmt = $db->Prepare("select count(*) from quiz	where id = ?");
		$stmt->bind_param("i", $quizId);
		$db->Execute($stmt);

		$count = 0;
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();
		return (0<$count);
	}
}