<?php
include_once("include.php");
AdminUser::Clear();
$site = new AdminSite();

if (isset($_POST['email'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];	

	$user = AdminUser::Assert($email, $password);
	if ($user) {
		$user->Save();
		Utils::Redirect("index.php");	
	}
} else {
	$email = "";
	$test = "";
	$password = "";
}

include("_header.php");

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
$err = Utils::Param("err", false);
if ($err) {
	echo '<div class="alert-box alert">' . $err . '</div>';
}
Flash::Render();
?>
<form method="post" action="login.php">
<label for="email">Email 
<input type="email" name="email" value="<?php echo $email; ?>" /></label>
<label for="password">Password
<input type="password" name="password" value="" /></label>
<input type="submit" class="button" value="Login" />
</form>
<a href="admin-forgot-password.php">Forgot password</a>
</div></div>
<?php
include("_footer.php");