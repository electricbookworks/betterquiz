<?php
include_once("global.db.php");
include_once("betterquiz/include.php");
include_once("class.Site.php");
include_once("class.Utils.php");
include_once("class.PanaceaSMS.php");
include_once("class.BulkSMS.php");

if (file_exists(dirname(__FILE__) . '/settings.local.php')) {
	include_once("settings.local.php");
}

