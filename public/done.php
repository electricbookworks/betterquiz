<?php
/**
 * @file
 * done.php is the final page a user visits before they are returned to the 
 * site from which they came.
 */
include_once("lib/include.php");

$examId = BQExam::GetSession();
$exam = BQExam::LoadExamById($db, $examId);
$exam->SetSubmit($db, Utils::Param("submit_results", 0));

$redirect = ReturnSite::GetSession();
if (!$redirect) {
	BQExam::ClearSession();
	Utils::Redirect("done_no_redirect.php", array("qz"=>$exam->QuizId()));
}
ReturnSite::ClearSession();
CssStore::ClearSession();
header("Location: $redirect");
