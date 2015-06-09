<?php
/**
 * @file
 * exit.php is the page the user comes to if they are exiting.
 * Their exam is closed and marked not for submission..
 */
include_once("lib/include.php");

if (array_key_exists("sure", $_GET)) {
	$examId = BQExam::GetSession();
	$exam = BQExam::LoadExamById($db, $examId);
	$exam->SetSubmit($db, 0);
	$exam->SetCompleted($db);

	$redirect = ReturnSite::GetSession();
	if (!$redirect) {
		echo "We don't have a redirect site: nowhere to go home to.";
		die();
	}
	ReturnSite::ClearSession();
	CssStore::ClearSession();
	header("Location: $redirect");
	die();
}

$src = Utils::Param("src","");

$excludeNav = true;
include("_header.php");
?>
<div class="exit">
<p>Are you sure you want to exit? The results of this quiz will be discarded?</p>
<div class="yes"><a href="exit.php?sure=yes">Yes</a></div>
<div class="no"><a href="<?php echo $src; ?>">No</a></div>
</div>

<?php
include("_footer.php");
