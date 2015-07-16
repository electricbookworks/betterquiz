<?php

/**
 * @file
 * register.php handles registration of new users.
 */
include_once("lib/include.php");

$qz = Utils::Param("qz", 0);
$uid = Utils::Param("uid", 0);
$fullname = Utils::Param("fullname");
$email = Utils::Param("email");
$mobile = Utils::Param("mobile");
$pass1 = Utils::Param("pass1");
$pass2 = Utils::Param("pass2");

$stage = Utils::Param("stage");
$registerStage = "register"==$stage;
$updateStage = !$registerStage;

$src = Utils::Param("src");

$errors = new Errors();
$fullname = trim($fullname);
if (""==$fullname) {
	$errors->Error("You need to provide a full name.");
}

$email = trim($email);
$mobile = trim($mobile);
if (""==$email && ""==$mobile) {
	$errors->Error("You need to provide either an email or a cell number.");
}
if (""!=$email) {
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors->Error("You need to provide a proper email address.");
	}
	// Todo: check that the user with the email is this user if not registerStage
	if ($registerStage && BQUser::UserEmailExists($db, $email)) {
		$errors->Error("A user already exists with that email address. Have you forgotten your password?");
	}
	if ($updateStage) {
		$otherId = BQUser::UserIdForEmail($db, $email);
		if ($otherId && $uid!=$otherId) {
			$errors->Error("Another user already exists with that email address.");			
		}
	}
}
if (""!=$mobile) {
 	if ($registerStage && BQUser::UserMobileExists($db, $mobile)) {
		$errors->Error("A user already exists with the cell number. Have you forgotten your password?");
	}
	if ($updateStage) {
		$otherId = BQUser::UserIdForMobile($db, $mobile);
		if ($otherId && $uid!=$otherId) {
			$errors->Error("Another user already exists with that cell number.");
		}
	}
}

$pass1 = trim($pass1);
$pass2 = trim($pass2);
if ($registerStage) {
	if (""==$pass1) {
		$errors->Error("You must provide a password.");
	}
}
if ($pass1!=$pass2) {
	$errors->Error("Passwords must match.");
}

if ($errors->Any()) {
	Utils::Redirect("register_form.php",$_REQUEST);
}

$user = new BQUser($uid, $fullname, $email, $mobile, $pass1);
$user->SaveToDatabase($db);
BQUser::StoreSession($user->Id());
// Once we're registered, we continue to the quiz, if we're registerStage
// otherwise back to wherever we came from
if ($registerStage)
	Utils::Redirect("quiz.php", array("qz"=>$qz));
Utils::Redirect($src);