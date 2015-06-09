<?php
/**
 * @file
 * The index page clears any existing logged in user, saves the return url and the quiz id,
 * and forwards the user to the login page.
 *
 * Expected parameters:
 *  qz - int - quiz ID
 *  r  - string - return URL
 */
include_once("lib/include.php");

BQUser::ClearSession();
ReturnSite::StoreSession(Utils::Param("r"));
CssStore::StoreSession(Utils::Param("css"));
Utils::Redirect("login_form.php", array("qz"=>Utils::Param("qz",0)));