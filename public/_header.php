<?php
/**
 * @file
 * header for inclusion in all web pages.
 */
?>
<!doctype html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>BettterCare Quiz</title>
  <link rel="stylesheet" href="css/site.css" >
  <?php CssStore::Link(); ?>
</head>
<body>
<div id="workspace">
<?php
	include("_nav.php");
