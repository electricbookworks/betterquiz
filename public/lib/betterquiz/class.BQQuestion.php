<?php

include_once("class.BQOption.php");


/**
 * A Question in a Quiz.
 */
class BQQuestion {
	var $_id;
	var $_question;
	var $_number;
	var $_options;
	
	public function __construct($id, $question, $number) {
		$this->_id = $id;
		$this->_question = bqf_trim($question);
		$this->_number = $number;
		$this->_options = array();
	}

	public function Id() {
		return $this->_id;
	}

	/**
	 * Return this question as a BQF string.
	 */
	public function BQFString() {
		$out = array();
		$out[] = BQF::WhiteString($this->_question);
		foreach ($this->_options as $o) {
			$out[] = $o->BQFString();
		}
		$out[] = "\n";
		return implode("", $out);
	}

	/**
	 * Add an Option to this Question.
	 */
	public function AddOption($correct, $content) {
		array_push($this->_options, new BQOption(0, $correct, $content));
	}

	/**
	 * Return the actual text of this question.
	 */
	public function Question() {
		return $this->_question;
	}

	/**
	 * Return the question text as HTML. This assumes the question
	 * text is stored in Markdown, so it uses a Markdown renderer
	 * to convert the question text into HTML.
	 */
	public function QuestionHtml() {
		return BQMarkdown::render($this->Question());
	}

	/**
	 * Return an array of the Options for this Question.
	 */
	public function Options() {
		return $this->_options;
	}

	/**
	 * NextQuestionId returns the ID of the next question, 
	 * or FALSE
	 * if there is no next question in the quiz.
	 * @return int ID of the next question or FALSE if
	 * there is no next question.
	 */
	public function NextQuestionId($db=FALSE) {
		$db = Database::Get($db);
		$stmt = $db->Prepare(<<<EOSQL
			select q2.id from
			question q
			inner join question q2
			on
				q2.quiz_id = q.quiz_id
			where
				q.id = ?
				and
				q2.question_number > q.question_number
			order by q2.question_number asc
			limit 0,1
EOSQL
		);
		$stmt->bind_param("i", $this->_id);
		$db->Execute($stmt);

		$id = FALSE;

		$stmt->bind_result($id);
		$stmt->fetch();
		$stmt->Close();
		return $id;
	}

	/**
	 * PreviousQuestionId returns the ID of the previous question, or FALSE
	 * if there is no previous question in the quiz.
	 */
	public function PreviousQuestionId($db=FALSE) {
		$db = Database::Get($db);
		$stmt = $db->Prepare(<<<EOSQL
			select q2.id from
			question q
			inner join question q2
			on
				q2.quiz_id = q.quiz_id
			where
				q.id = ?
				and
				q2.question_number < q.question_number
			order by q2.question_number desc
			limit 0,1
EOSQL
		);
		$stmt->bind_param("i", $this->_id);
		$db->Execute($stmt);

		$id = FALSE;

		$stmt->bind_result($id);
		$stmt->fetch();
		$stmt->Close();
		return $id;
	}

	/**
	 * Return TRUE if this Question is identical to
	 * another, FALSE otherwise.
	 */
	public function Equals($that) {
		if (
			($this->_question != $that->_question) ||
			(count($this->_options)!=count($that->_options))
			)
			return false;
		for ($i=0; $i<count($this->_options); $i++) {
			if (!$this->_options[$i]->Equals($that->_options[$i])) 
				return false;
		}
		return true;
	}

	public function SaveToDatabase($db, $quiz, $number) {
		$db = Database::Get($db);
		if (0==$this->_id) {
			$stmt = $db->Prepare("insert into question (quiz_id, question_text, question_number) values (?,?,?)");
			$stmt->bind_param("isi", $quiz->Id(), $this->_question, $number);
			$db->Execute($stmt);
			$this->_id = $db->LastInsertId();
		} else {
			$stmt = $db->prepare("update question set question_text=?, question_number=? where id=?");
			$stmt->bind_param("sii", $this->_question, $number, $this->_id);
			$db->Execute($stmt);
		}
		if (0==count($this->_options)) {
			$db->Query("delete from options where question_id=" . $this->_id);
		} else {
			$keys = array();
			for ($i=0; $i<count($this->_options); $i++) {
				$this->_options[$i]->SaveToDatabase($db, $this, $i);
				$keys[] = $this->_options[$i]->Id();
			}

			$db->Query("delete from options where question_id=" .
				$this->_id . " and not id in (" . implode(",", $keys) . ")");
		}
	}

	/**
	 * Return all the questions for the given Quiz.
	 */
	public static function LoadQuestionsForQuiz($db, $quizId) {
		$questions = array();
		$stmt = $db->Prepare("select id, question_text, question_number from question where " .
			"quiz_id=? order by question_number asc");
		$stmt->bind_param("i", $quizId);
		$db->Execute($stmt);

		$id = NULL;
		$question = NULL;
		$number = NULL;

		$stmt->bind_result($id, $question, $number);
		while ($stmt->fetch()) {
			$q = new BQQuestion($id, $question, $number);
			$q->_id = $id;
			$questions[] = $q;
		}
		for ($i=0; $i<count($questions); $i++) {
			$questions[$i]->_options = BQOption::LoadOptionsForQuestion($db, $questions[$i]->_id);
		}
		return $questions;
	}

	public static function LoadQuestionById($db, $questionId) {
		$stmt = $db->Prepare("select question_text, question_number from question where " . " id=?");
		$stmt->bind_param("i", $questionId);
		$db->Execute($stmt);

		$question = NULL;
		$number = NULL;

		$stmt->bind_result($question, $number);
		if (!$stmt->fetch()) {
			return null;
		}
		$stmt->Close();

		$q = new BQQuestion($questionId, $question, $number);
		$q->_options = BQOption::LoadOptionsForQuestion($db, $q->_id);
		return $q;
	}
}
