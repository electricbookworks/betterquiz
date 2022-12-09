<?php
/**
 * @file
 * login.php handles actual login or registration, forwarding the user to 
 * the login or the registration page, or the forgotten password page if appropriate.
 */
include_once("lib/include.php");

$act = $_REQUEST["act"];
$qz = intval($_REQUEST["qz"]);	// qz is quiz ID and is carried through everything
$email = $_REQUEST["email"];
$password = $_REQUEST["password"];

if ("login / register"==$act) {
	if (0==strlen(trim($email))) {
		Utils::Redirect("register_form.php", array("qz"=>$qz));		
	}
	list ($userId, $ok) = BQUser::VerifyPassword($db, $email, $password);
	if (!$ok) {
		if ("bad-password"==$userId) {
			Flash::New("Sorry, the password is incorrect.", "error");
			Utils::Redirect("login_form.php", 
				array("qz"=>$qz, "email"=>$email));
		}
		Flash::New("Sorry, I don't recognize that " . (Utils::IsEmail($email) ? "email" : "cell number") . ". You can register here.", "error");
		$p = array("qz"=>$qz);
		if (Utils::IsEmail($email)) {
			$p["email"] = $email;
		} else {
			$p["mobile"] = $email;
		}
		Utils::Redirect("register_form.php", $p);
	}
	$user = BQUser::LoadUserById($db, $userId);
	if ($user->IsMerged()) {
		$actual = $user->GetActualUser();
		Flash::New("Sorry, that user account has been merged with " .
			$actual->EmailOrMobile(). ". Please login with that account.", "error");
		Utils::Redirect("login_form.php", array("qz"=>$qz, "email"=>$actual->EmailOrMobile()));
	}
}
if ("forgot password"==$act) {
	Utils::Redirect("forgot_form.php", array("qz"=>$qz, "email"=>$email));
}
if ("register"==$act) {
	Utils::Redirect("register_form.php", array("qz"=>$qz, "email"=>$email));
}

// We're logged in successfully
BQUser::StoreSession($userId);
Utils::Redirect("quiz.php", array("qz"=>$qz));