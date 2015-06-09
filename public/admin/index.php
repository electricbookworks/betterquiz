<?php
require_once("include.php");;

$user = AdminUser::Get();
if (null==$user) {
	header("Location: login.php");
	die();
}
$site = new AdminSite();

include_once("_header.php");
include_once("_nav.php");
?>
<div class="row">
<div class="large-12 columns">
<h1>bettercare quiz engine</h1>
</div></div>
<?php
include_once("_footer.php");