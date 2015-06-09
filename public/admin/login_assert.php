<?php
/**
 * @file login_assert.php receives a Persona assertion and audience, and checks that it is valid.
 */
include_once("include.php");

$assert = $_GET["assertion"];
// @TODO Audience should be auto-generated on our server, not accepted from the client
$audience = $_GET["audience"];
try {
	$user = Persona::Assert($assert, $audience);
	$user->Save();
	Utils::Redirect("index.php");
} catch (Exception $e) {
	?>
<html>
<head>
<script src="https://login.persona.org/include.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	navigator.id.logout();
	document.location="login.php?err=<?php echo urlencode($e->getMessage()); ?>";
});
</script>
</head>
</html>
<?php
	die();
}