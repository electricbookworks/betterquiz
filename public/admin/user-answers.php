<?php
include_once("include.php");

$user = AdminUser::Get();
if (!$user) {
	die("You need to login");
}
header("Content-Type: text/plain");

$userId = Utils::Param("user_id", 0);
$exams = BQUser::LoadExamsForUser($userId);

$out = fopen('php://output', 'w');
fputcsv($out, BQExam::CsvArrayHeader());
foreach ($exams as $e) {
	fputcsv($out, $e->CsvArray());
}

fclose($out);
