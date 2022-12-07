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
  <title>Bettercare Installation</title>
  <link rel="stylesheet" href="css/site.css" >
  <script src="api.php"> </script>
  <script src="js/BQInstall.js"> </script>
<style>
body {
	display: flex;
	flex-direction: row;
	justify-content: center;
	align-items: center;
	height: 100vh;
	width: 100vw;
}
.grid {
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: stretch;
	width: 80%;
	max-width: 30em;
}
bq-install {
	flex-grow: 1;
}
</style>
</head>
<body>
	<div class="grid">
		<bq-install> </bq-install>
	</div>
</body></html>
