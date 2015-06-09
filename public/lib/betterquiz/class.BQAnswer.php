<?php

/**
 * BQAnswer models an answer given by a user to a particular
 * question in a particular quiz.
 */
class BQAnswer {
	protected $_question;
	protected $_chosenOption;
	protected $_correctOption;

	public function __construct($question, $chosenOption) {
		$this->_question = $question;
		$this->_chosenOption = $chosenOption;
	}

	/**
	 * Returns the Question for this Answer.
	 */
	public function Question() {
		return $this->_question;
	}
	/**
	 * Returns the text of the Question for this answer.
	 */
	public function QuestionText() {
		return $this->_question->Question();
	}
	/**
	 * Returns the option that was chosen.
	 */
	public function ChosenOption() {
		return $this->_chosenOption;
	}
	/**
	 * Returns the correct Option for this Question.
	 * This could differ from the ChosenOption(), in which
	 * case the user was wrong.
	 */
	public function CorrectOption() {
		return $this->_chosenOption->GetCorrectOption();
	}
	/**
	 * Return true if the answer is correct.
	 */
	public function IsCorrect() {
		return $this->_chosenOption->Correct();
	}

	/**
	 * Returns the Answer if it is the answer to the
	 * question with the given ID, or false otherwise.
	 */
	public function AnswerForQuestion($qid) {
		if ($this->_question->Id()==$qid) {
			return $this;
		}
		return false;
	}

	/**
	 * Returns an array of BQAnswers for the exam 
	 * with the given exam ID.
	 * @return []BQAnswer Array of BQAnswer for the given
	 * exam.
	 */
	public static function LoadAnswersForExam($db, $examId) {
		$answers = [];

		$db = Database::Get($db);
		$stmt = $db->Prepare(<<<EOSQL
			select 
				o.id, o.option_text, o.correct, o.option_number,
				q.id, q.question_text, q.question_number
			from 
				answer a
				inner join option o
					on o.id = a.option_id
				inner join question q
					on o.question_id = q.id
			where
				a.exam_id = ?
			order by q.question_number
EOSQL
		);
		$stmt->bind_param("i", $examId);
		$res = $db->Execute($stmt);

		$optionId = null;
		$optionText = null;
		$optionCorrect = false;
		$optionNumber = 0;
		$questionId = null;
		$questionText = null;
		$questionNumber = 0;

		$stmt->bind_result($optionId, $optionText, $optionCorrect, $optionNumber,
			$questionId, $questionText, $questionNumber);

		while ($stmt->fetch()) {
			$o = new BQOption($optionId, $optionCorrect, $optionText, $optionNumber);
			$q = new BQQuestion($questionId, $questionText, $questionNumber);
			$answers[] = new BQAnswer($q, $o);
		}
		return $answers;
	}
}