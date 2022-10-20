<?php

/**
 * BQOption is a single option that a user can choose 
 * for a single BQQuestion in a
 * BQQuiz.
 */
class BQOption {
	var $_id;
	var $_correct;
	var $_option;
	var $_number;
	
	public function __construct($id, $correct, $content, $number=false) {
		$this->_id = $id;
		$this->_correct = $correct;
		$this->_option = bqf_trim($content);
		$this->_number = $number;
	}

	public function Id() {
		return $this->_id;
	}

	public function Number() {
		return $this->_number;
	}

	/**
	 * LoadById loads an option with the given option ID. It returns FALSE if no 
	 * such option could be found.
	 */
	public static function LoadById($db, $optionId) {
		$db = Database::Get($db);
		$stmt = $db->Prepare("select id, option_text, correct, option_number from options where id=?");
		$stmt->bind_param("i", $optionId);
		return self::loadFromStmt($db, $stmt);
	}

	protected static function loadFromStmt($db, $stmt) {
		$db->Execute($stmt);
		$id = null;
		$text = null;
		$correct = 0;
		$number = 0;
		$stmt->bind_result($id, $text, $correct, $number);
		if (!$stmt->fetch()) {
			$stmt->Close();
			return false;
		}
		$stmt->Close();
		return new BQOption($id, $correct, $text, $number);
	}

	/**
	 * GetCorrectOption returns the correct option for the question to which
	 * this option applies.
	 * Of course, if this option is correct, it is itself returned.
	 */
	public function GetCorrectOption($db=false) {
		if ($this->_correct) {
			return $this;
		}
		$db = Database::Get($db);
		$stmt = $db->Prepare(<<<EOSQL
			select o2.id, o2.option_text, o2.correct, o2.option_number
			from options o
				inner join options o2 on o.question_id = o2.question_id
			where
				o2.correct=1
				and o.id=?
EOSQL
		);
		$optionId = $this->_id;
		$stmt->bind_param("i", $optionId);
		return self::loadFromStmt($db, $stmt);
	}

	/**
	 * Return the actual text of this Option.
	 */
	public function Option() {
		return $this->_option;
	}

	/**
	 * Return the option Markdown rendered as HTML.
	 */
	public function OptionHtml() {
		return BQMarkdown::render($this->Option());
	}

	/**
	 * Correct returns true if this option is the correct option, false otherwise.
	 */
	public function Correct() {
		return $this->_correct;
	}

	/**
	 * Return true if this Option is the same as another
	 */
	public function Equals($that) {
		return (
		 	($this->_correct == $that->_correct) &&
		 	($this->_option = $that->_option)
		 	);
	}

	/**
	 * Return this Option in BQF format.
	 */
	public function BQFString() {
		return BQF::WhiteString( ($this->_correct ? "+" : "-") . " " . $this->_option);
	}

	public function SaveToDatabase($db, $question, $number) {
		$correct = $this->_correct ? 1 : 0;
		$optionId = $this->_id;
		$questionId = $question->Id();
		$option = $this->_option;
		if (0==$this->_id) {
			$stmt = $db->Prepare(
				"insert into options (question_id, option_number, option_text, correct) values (?,?,?,?)");
			$stmt->bind_param("iisi", $questionId, $number, $option, $correct);
			$db->Execute($stmt);
			$this->_id = $db->LastInsertId();
		} else {
			$stmt = $db->Prepare(
				"update options set question_id=?, option_number=?, option_text=?, correct=? where id=?");
			$stmt->bind_param("iisii", $questionId, $number, $option, $correct, $optionId);
			$db->Execute($stmt);
		}
		$stmt->Close();
	}

	/**
	 * LoadOptionsForQuestion returns all the BQOptions for the given BQQuestion
	 */
	public static function LoadOptionsForQuestion($db, $questionId) {
		$options = array();
		$stmt = $db->Prepare(<<<EOSQL
			select id, option_text, correct, option_number 
			from options where 
			question_id=? order by option_number asc
EOSQL
		);
		$stmt->bind_param("i", $questionId);
		if (!$db->Execute($stmt)) {
			throw new RuntimeException($db->error);
		}

		$id = NULL;
		$option = NULL;
		$correct = NULL;
		$number = false;
		$stmt->bind_result($id, $option, $correct, $number);
		while ($stmt->fetch()) {
			$o = new BQOption($id, $correct, $option, $number);
			$options[] = $o;
		}
		$stmt->Close();
		return $options;
	}
}
