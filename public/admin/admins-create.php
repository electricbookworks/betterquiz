<?php
include_once("include.php");
$user = AdminUser::Secure();
$site = new AdminSite();

$email = Utils::Param("email", "");
BQAdmin::Create($email);
Utils::Redirect("admins-list.php");