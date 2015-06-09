<?php
/**
 * @file
 * The Quiz page allows the user to login if they've not already logged in,
 * and forwards them to the appropriate question for the quiz.
 *
 * Expected parameters:
 *  qz - int - quiz ID
 */
include_once("lib/include.php");

$userId = BQUser::GetSession();
if (!$userId) {
	Utils::Redirect("login_form.php", array("qz"=>$_REQUEST["qz"]));
}
$quizId = $_REQUEST["qz"];
$questionId = BQQuiz::FindFirstQuestionId($db, $quizId);

BQExam::StartExam($db, $quizId, $userId);
Utils::Redirect("question.php", array("q"=>$questionId));