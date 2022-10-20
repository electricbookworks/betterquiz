<?php

/**
 * BQExam is a test that a BQUser is taking.
 * It therefore links a BQQuiz with a BQUser.
 * It is possible for a user to take a quiz multiple times, 
 * so there is not a
 * unique BQExam per user-quiz combination.
 */
class BQExam {
	protected $_id;
	protected $_answers;
	protected $_userId;

	protected $_score;	///< _score is calculated from the _answers array and cached

	public function __construct($id, $answers=false, $userId=false, $quizId=false, $startDate=false, $endDate=null, $submitted=false) {
		$this->_id = $id;
		$this->_answers = $answers;
		$this->_score = FALSE;
		$this->_userId = $userId;
		$this->_quizId = $quizId;
		$this->_startDate = $startDate;
		$this->_endDate = $endDate;
		$this->_submitted = $submitted;
	}

	/**
	 * AnswerForQuestion returns the answer for the given question ID, or FALSE
	 * if the question was not answered.
	 */
	public function AnswerForQuestion($qid) {
		foreach ($this->_answers as $a) {
			$a = $a->AnswerForQuestion($qid);
			if ($a) {
				return $a;
			}
		}
		return false;
	}

	/**
	 * QuizId returns the ID of the quiz for this exam.
	 */
	public function QuizId() {
		return $this->_quizId;
	}

	/**
	 * CsvArrayHeader returns the headers for the fields in the CSV output
	 */
	public static function CsvArrayHeader() {
		return array(
			"QuizId","QuizTitle",
			"UserEmail", "UserMobile","UserFullname",
			"StartDate","EndDate","Submitted","Score","Total",
			"Q1Answer","Q1Correct","Q2Answer","Q2Correct","...");
	}

	/** 
	 * CsvArray returns the array of results to place into a CSV file for this answer
	 */
	public function CsvArray($quiz=false) {
		if (false===$quiz) {
			$quiz = BQQuiz::LoadFromDatabase(false, $this->_quizId);
		}
		$r = array(
			$quiz->Id(),
			$quiz->Title(),
		);	// The current rows
		$user = $this->User();
		if ($user) {
			$r[] = $user->Email();
			$r[] = $user->Mobile();
			$r[] = $user->Fullname();
		} else {
			$r[] = "";
			$r[] = "";
			$r[] = "";
		}
		$r[] = $this->StartDate();
		$r[] = $this->EndDate();
		$r[] = $this->Submitted();
		$r[] = $this->Score();
		$r[] = $this->Total();
		foreach ($quiz->Questions() as $q) {
			$a = $this->AnswerForQuestion($q->Id());
			if (false==$a) {
				$r[] = "";
				$r[] = 0;
			} else {
				$r[] = $a->ChosenOption()->Number();
				$r[] = $a->IsCorrect() ? 1 : 0;
			}
		}
		return $r;
	}
	/**
	 * StartDate returns the start date of the exam.
	 */
	public function StartDate() {
		return $this->_startDate;
	}
	public function EndDate() {
		return $this->_endDate;
	}
	public function Submitted() {
		return $this->_submitted;
	}
	public function Answers() {
		if (false!==$this->_answers) {
			return $this->_answers;
		}
		$this->_answers = BQAnswer::LoadAnswersForExam(false, $this->_id);
		return $this->_answers;
	}

	public function User() {
		if (false===$this->_userId) {
			return false;
		}
		return BQUser::LoadUserById(false, $this->_userId);
	}

	/**
	 * Returns the Score the user achieved for the exam.
	 */
	public function Score() {
		if (FALSE !== $this->_score) {
			return $this->_score;
		}
		$this->_score = 0;
		foreach ($this->Answers() as $a) {
			if ($a->IsCorrect()) {
				$this->_score++;
			}
		}
		return $this->_score;
	}

	/**
	 * Returns the maximum possible score achievable
	 * for the Quiz.
	 */
	public function Total() {
		return count($this->_answers);
	}

	public function Percentage() {
		if (0==$this->Total()) {
			return 0;
		}
		return round($this->Score() * 100.0) / ($this->Total() * 1.0);
	}

	public static function LoadExamById($db, $examId) {
		$db = Database::Get($db);
		$stmt = $db->Prepare(
			"select quiz_id, user_id, startdate, enddate, submitted " .
			" from exam where id=?"
			);
		$stmt->bind_param("i", $examId);
		$db->Execute($stmt);
		$quizId = 0;
		$userId = 0;
		$startDate = null;
		$endDate = null;
		$submitted=false;
		$stmt->bind_result($quizId, $userId, $startDate, $endDate, $submitted);
		$stmt->fetch();
		$stmt->Close();
		return new BQExam($examId, false, $userId, $quizId, $startDate, $endDate, $submitted);
	}

	/**
	 * Loads all exams taken for a given Quiz.
	 */
	public static function LoadAllForQuiz($db, $quizId) {
		$db = Database::Get($db);
		$all = array();
		$stmt = $db->Prepare("select id from exam " .
			"where quiz_id=? order by startdate asc");
		$stmt->bind_param("i", $quizId);
		$db->Execute($stmt);
		$ids = array();
		$examid = 0;
		$stmt->bind_result($examid);
		while ($stmt->fetch()) {
			$ids[] = $examid;
		}
		$stmt->Close();
		return array_map(function($id) {
			return self::LoadExamById(false, $id);
		}, $ids);
	}

	public static function StartExam($thedb=false, $quizId, $userId) {
		if (!$thedb) {
			global $db;
			$thedb = $db;
		}
		$stmt = $thedb->Prepare("insert into exam (quiz_id, user_id, startdate) " .
			" values (?,?,now())");
		$stmt->bind_param("ii", $quizId, $userId);
		$thedb->Execute($stmt);
		$id = $thedb->LastInsertId();
		$stmt->Close();
		self::StoreSession($id);
		return $id;
	}

	public static function SetExamAnswer($thedb=false, $examId, $optionId) {
		if (!$thedb) {
			global $db;
			$thedb = $db;
		}
		if (false===$examId) {
			error_log(__FILE__ . ":" . __LINE__ . " : SetExamAnswer(_, $examId, $optionId) : examId is invalid");
			return;
		}

		// Remove any existing answer to this question
		$stmt = $thedb->Prepare(<<<EOSQL
			delete from answer
			where exam_id = ?
			and option_id in (
				select o2.id from options o
					inner join options o2
					on o.question_id=o2.question_id
				where
					o.id=?
			)
EOSQL
		);
		$stmt->bind_param("ii", $examId, $optionId);
		$thedb->Execute($stmt);
		$stmt->Close();

		$stmt = $thedb->Prepare("insert into answer" .
			"(exam_id, option_id) " .
			" values (?,?)");
		$stmt->bind_param("ii", $examId, $optionId);
		$thedb->Execute($stmt);
		$stmt->Close();
	}

	public static function ScoreExam($thedb=false, $examId) {
		if (!$thisdb) {
			global $db;
			$thedb = $db;
		}
		$stmt = $thedb->Prepare(<<<EOSQL
			select sum(o.correct) as score, count(*) as total
			from
				answer a
					inner join options o 
					on a.option_id = o.id
			where
				a.exam_id=?
EOSQL
		);
		$stmt->bind_param("i", $examId);
		$thedb->Execute($stmt);
		$score = 0;
		$total = 0;
		$stmt->bind_result($score, $total);
		$stmt->fetch();
		$stmt->Close();

		return array($score, $total);
	}

	public static function GetOptionForQuestion($thedb, $examId, $questionId) {
		if (!$thedb) {
			global $db;
			$thedb = $db;
		}
		$stmt = $thedb->Prepare(<<<EOSQL
			select a.option_id
			from
				answer a
				inner join options o
					on o.id= a.option_id
			where
				o.question_id=?
			and
				a.exam_id=?
EOSQL
		);
		$stmt->bind_param("ii", $questionId, $examId);
		$thedb->Execute($stmt);
		$o = false;
		$stmt->bind_result($o);
		$stmt->fetch();
		$stmt->Close();
		return $o;
	}

	public static function StoreSession($examId) {
		SessionStore::Store("exam_id", $examId);
	}

	public static function ClearSession() {
		SessionStore::Clear("exam_id");
	}

	public static function GetSession() {
		return SessionStore::Get("exam_id");
	}

	public function SetCompleted($db) {
		$db = Database::Get($db);
		$stmt = $db->Prepare("update exam set enddate=now() where id=? and enddate is null");
		$examId = $this->_id;
		$stmt->bind_param("i", $examId);
		$db->Execute($stmt);
		$stmt->Close();		
	}

	public function SetSubmit($db, $submit) {
		$db = Database::Get($db);
		$stmt = $db->Prepare("update exam set submitted=? where id=?");
		$submit = $submit ? 1 : 0;
		$examId = $this->_id;
		$stmt->bind_param("ii", $submit, $examId);
		$db->Execute($stmt);
		$stmt->Close();
	}
	
}