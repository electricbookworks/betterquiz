<?php
include_once("include.php");
$user = AdminUser::Secure();
$site = new AdminSite();

$email = Utils::Param("email", "");
BQAdmin::Delete($email);
Utils::Redirect("admins-list.php");