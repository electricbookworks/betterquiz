<?php
include_once("include.php");
AdminUser::Clear();
$site = new AdminSite();

$error = false;
if ("POST"==$_SERVER["REQUEST_METHOD"]) {
	$email = $_POST["email"];
	$res = AdminUser::GeneratePasswordResetRequest($email);
	if (!ErrorMessage::IsError($res)) {
		Flash::New("A password reset link has been sent to $email. Please check your email.");
		header("Location: login.php");
		die();
	}
	if (is_object($res)) {
		$error = $res->Message();
	} else {
		die(__FILE__.":".__LINE__." : Unreachable code");
	}
}

include("_header.php");

$email = isset($_POST['email']) ? $_POST['email'] : '';

?>
<nav class="top-bar" data-topbar role="navigation">
	<ul class="title-area">
		<li class="name"><h1><a href="/">betterquiz</a></h1></li> 
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
</nav>

<div class="row">
<div class="small-12 columns">
<?php
$err = $error ? $error : Utils::Param("err", false);
if ($err) {
	echo '<div class="alert-box alert">' . $err . '</div>';
}
?>
<form method="post" action="admin-forgot-password.php">
<label for="email">Email 
<input type="email" name="email" value="<?php echo $email; ?>" /></label>

<input type="submit" class="button" value="Request Password Reset" />
</form>
<div>
<a href="login.php">Back to Login</a>
</div>
</div></div>
<?php
include("_footer.php");