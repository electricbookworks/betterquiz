<?php
/**
 * @file
 * forgot_form.php presents the user with the forgotten password form.
 */
include_once("lib/include.php");

$excludeNav = true;
include("_header.php");
$qz = Utils::Param("qz",0);
$email = Utils::Param("email");

Flash::Render();
?>
<div id="forgot-form">
<form method="post" action="forgot.php">
<input type="hidden" name="qz" value="<?php echo $qz; ?>" />
<label for="email">Email / Cell number</label>
<input type="text" name="email" id="email" value="<?php echo $email; ?>" />
<input type="submit" name="act" value="forgot password" class="forgot-password" />
<input type="submit" name="act" value="Login / Register" class="login-register" />
</form>
</div>
<?php
include("_footer.php");