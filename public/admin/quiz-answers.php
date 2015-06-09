<?php
include_once("include.php");

$user = AdminUser::Get();
if (!$user) {
	die("You need to login");
}
header("Content-Type: text/plain");

$quizId = Utils::Param("quiz_id", 0);
$quiz = BQQuiz::LoadFromDatabase($db, $quizId);

$out = fopen('php://output', 'w');
fputcsv($out, BQExam::CsvArrayHeader());
$exams = BQExam::LoadAllForQuiz($db, $quizId);
foreach ($exams as $e) {
	fputcsv($out, $e->CsvArray($quiz));
}

fclose($out);
