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

$qz = Utils::Param("qz", 0);

if (!BQQuiz::DoesQuizExist($qz)) {
	$ref = $_SERVER["HTTP_REFERER"];
	$me = $_SERVER["HTTP_HOST"];
	mail("craig@lateral.co.za",
		"BetterQuiz: quiz not found($qz) - referer $ref",
		<<<EOMSG
Hi,

We've just had an attempt to access quiz number $qz on $me. Unfortunately,
we don't seem to have any such quiz.

The referer was $ref.

Hope you can fix it.

BetterQuiz

EOMSG
		);
	Utils::Redirect("no_such_quiz.php", array("qz"=>$qz));
}

$userId = BQUser::GetSession();
if (!$userId) {
	Utils::Redirect("login_form.php", array("qz"=>$qz));
}
$questionId = BQQuiz::FindFirstQuestionId($db, $qz);

BQExam::StartExam($db, $qz, $userId);
Utils::Redirect("question.php", array("q"=>$qz));