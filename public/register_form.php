<?php
/**
 * @file
 * register_form.php presents the user with the registration form, or the profile
 * update form, depending on the value of the 'uid' parameter the form receives.
 */
include_once("lib/include.php");

$excludeNav = true;
include("_header.php");
$qz = Utils::Param("qz", 0);
$uid = Utils::Param("uid", 0);
$user = BQUser::LoadUserById($db, $uid);

$fullname = Utils::Param("fullname", $user->Fullname());
$email = Utils::Param("email", $user->Email());
$mobile = Utils::Param("mobile", $user->Mobile());
$pass1 = Utils::Param("pass1");
$pass2 = Utils::Param("pass2");

Flash::Render();
?>
<div id="register-form">
<form method="post" action="register.php">
<input type="hidden" name="qz" value="<?php echo $qz; ?>" />
<label for="fullname">Full name</label>
<input type="text" name="fullname" id="fullname" value="<?php echo $fullname; ?>" />
<label for="email">Email</label>
<input type="text" name="email" id="email" value="<?php echo $email; ?>" />
<label for="mobile">Cell number</label>
<input type="text" name="mobile" id="mobile" value="<?php echo $mobile; ?>" />
<label for="pass1">Password</label>
<input type="password" name="pass1" id="pass1" value="<?php echo $pass1; ?>" />
<label for="pass2">Password (repeat)</label>
<input type="password" name="pass2" id="pass2" value="<?php echo $pass2; ?>" />
<?php
	if (0==$uid) {
?>
<a href="<?php echo Utils::Url("forgot_form.php", array("qz"=>$qz)); ?>">Forgot password</a>
<a href="<?php echo Utils::Url("login_form.php", array("qz"=>$qz)); ?>">Login</a>
<input type="hidden" name="stage" value="register" />
<input type="submit" name="act" value="Register" class="register" />
<?php
	} else {
?>
<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
<input type="hidden" name="src" value="<?php echo Utils::Param("src"); ?>" />
<input type="hidden" name="stage" value="update" />
<input type="submit" name="act" value="Update" class="register" />
<?php
	}
?>
</form>
</div>
<?php
include("_footer.php");