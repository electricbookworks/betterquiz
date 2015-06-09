<?php
/**
 * @file
 * post_question.php stores a user's response to a question, and redirects to the next
 * question in the quiz, or to the score page if all questions are answered.
 */
include_once("lib/include.php");

$qid = intval($_POST["q"]);
$q = BQQuestion::LoadQuestionById($db, $qid);

if (!array_key_exists("o$qid", $_POST)) {
	// The user hasn't chosen an option for the previous question
	new Flash("Please choose one of the options for this question.");
	Utils::Redirect("question.php", array("q"=>$qid));
}
$o = intval($_POST["o$qid"]);

$examId = BQExam::GetSession();
BQExam::SetExamAnswer($db, $examId, $o);

$nextQ = $q->NextQuestionId();
if ($nextQ) {
	Utils::Redirect("question.php", array("q"=>$nextQ));
}
Utils::Redirect("score_form.php");