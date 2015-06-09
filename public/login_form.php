<?php
/**
 * @file
 * login_form.php presents the user with the login form.
 */
include_once("lib/include.php");

$excludeNav = true;
include("_header.php");
$qz = Utils::Param("qz",0);
$email = Utils::Param("email");

Flash::Render();
?>
<div id="login-form">
<form method="post" action="login.php">
<input type="hidden" name="qz" value="<?php echo $qz; ?>" />
<label for="email">Email / Cell Number</label>
<input type="text" name="email" id="email" value="<?php echo $email; ?>" />
<label for="password">Password</label>
<input type="password" name="password" id="password" value="" />
<input type="submit" name="act" value="login / register" class="login" />
<input type="submit" name="act" value="forgot password" class="forgot-password" />
<!-- <input type="submit" name="act" value="register" class="register" /> -->
</form>
</div>
<?php
include("_footer.php");