<?php
/**
 * @file
 * admins-password-reset.php is used to reset a forgotten
 * password. The user should come to this page from an email
 * sent when requesting a password reset.
 */

include_once("include.php");
AdminUser::Clear();
$site = new AdminSite();

$error = false;
if ("POST"==$_SERVER["REQUEST_METHOD"]) {
	$email = $_POST["email"];
	$code = $_POST["code"];
	$pwd1 = $_POST["password1"];
	$pwd2 = $_POST["password2"];
	if ($pwd1!=$pwd2) {
		$error = "You need to enter the same password into both fields.";
	} else if (strlen($pwd1)<3) {
		$error = "Your password needs to be at least 3 characters long.";
	} else {
		$res = AdminUser::ResetPassword($email, $code, $pwd1);
		if (!ErrorMessage::IsError($res)) {
			new Flash("Your password has been reset. Please log in.");
			header("Location: login.php");
			die();
		}
		$error = $res->Message();
	}
}

include("_header.php");

$email = isset($_POST['email']) ? $_POST['email'] : $_GET["email"];

?>
<nav class="top-bar" data-topbar role="navigation">
	<ul class="title-area">
		<li class="name"><h1><a href="/">betterquiz</a></h1></li> 
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
</nav>

<div class="row">
<div class="small-12 columns">
<h1>Password Reset: <?php echo $email; ?></h1>
<?php
$err = $error ? $error : Utils::Param("err", false);
if ($err) {
	echo '<div class="alert-box alert">' . $err . '</div>';
}
Flash::Render();
?>
<form method="post" action="<?php $u = new SelfUrl(); echo $u->url(); ?>">
<input type="hidden" name="email" value="<?php echo $_GET["email"]; ?>" />
<input type="hidden" name="code" value="<?php echo $_GET["code"]; ?>" />
<label for="password1">New Password
<input type="password" id="password1" name="password1" value="" /></label>
<label for="password2">New Password (repeat)
<input type="password" id="password2" name="password2" value="" /></label>

<input type="submit" class="button" value="Reset Password" />
</form>
</div></div>
<?php
include("_footer.php");