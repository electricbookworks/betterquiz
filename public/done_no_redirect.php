<?php
/**
 * @file
 * User comes here if they have completed the quiz, but we don't 
 * have any redirect information for them.
 */
include_once("lib/include.php");
$qz = Utils::Param("qz");
?><!doctype html>
<html>
<head>
<script>
if (window != window.top) {
	// User is in an iframe
	document.location = "quiz.php?qz=<?php echo $qz;?>";
} else {
	document.location = "http://ls.bettercare.co.za";
}
</script>
</head>
</html>