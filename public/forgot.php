<?php
/**
 * @file
 * forgot.php handles forgotten passwords, sending either an email
 * to reset the password, or an SMS with a password reset code.
 */
include_once("lib/include.php");

$act = Utils::Param("act");
$qz = Utils::Param("qz");
$email = Utils::Param("email");

$errors = new Errors();
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
	// We're dealing with an email address
	$uid = BQUser::UserIdForEmail($db, $email);
	if (!$uid) {
		$errors->Error("Sorry, we can't find that email address in our database. Are you sure you've registered?.");
		Utils::Redirect("forgot_form.php", $_REQUEST);
	}
	// We've got the userid, so we've got a valid email address
	$new = BQUserForgot::NewPassword($db, $email);
	if (!$new) {
		$errors->Error("Can't find that user. (Should not get here.)");
		Utils::Redirect("forgot_form.php", $_REQUEST);
	}
	// @TODO Send email about forgotten password
	error_log("About to call BQUserForget::EmailPassword($uid, $email, $new)");
	BQUserForgot::EmailPassword($uid, $email, $new);
	new Flash("We've emailed you a new password.");
	Utils::Redirect("login_form.php", $_REQUEST);
}

$mobile = Utils::FilterMobile($email);
if (!$mobile) {
	$errors->Error("Please enter either an email address or a cell number.");
	Utils::Redirect("forgot_form.php", $_REQUEST);
}
$uid = BQUser::UserIdForMobile($db, $mobile);
if (!$uid) {
	$errors->Error("Sorry, we can't find that cell number in our database.");
	Utils::Redirect("forgot_form.php", $_REQUEST);
}
$new = BQUserForgot::NewPassword($db, $mobile);
if (!$new) {
	$errors->Error("Can't find that user by mobile. (Should not get here.)");
	Utils::Redirect("forgot_form.php", $_REQUEST);
}	
// @TODO Send SMS with new password
BQUserForgot::SMSPassword($uid, $mobile, $new);
new Flash("We've SMS'd you a new password.");
Utils::Redirect("login_form.php", $_REQUEST);
