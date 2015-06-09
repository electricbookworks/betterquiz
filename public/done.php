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
	echo "We don't have a redirect site: nowhere to go home to.";
	die();
}
ReturnSite::ClearSession();
CssStore::ClearSession();
header("Location: $redirect");
