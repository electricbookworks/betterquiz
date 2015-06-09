<?php
include_once("include.php");

$user = AdminUser::Get();
if (!$user) {
	die("You need to login");
}
header("Content-Type: text/plain");

$quiz = BQQuiz::LoadFromDatabase($db, $_GET["quiz_id"]);
echo $quiz->BQFString();
