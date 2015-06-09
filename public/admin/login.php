<?php
include_once("include.php");
AdminUser::Clear();

$site = new AdminSite();
include("_header.php");
?>
<nav class="top-bar" data-topbar role="navigation">
	<ul class="title-area">
		<li class="name"><h1><a href="/">betterquiz</a></h1></li> 
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<ul class="right">
			<li><button id="persona">Login</button></li>
		</ul> 

		<ul class="left">
		</ul> 
	</section> 
</nav>

<div class="row">
<div class="small-12 columns">
<?php
$err = Utils::Param("err", false);
if ($err) {
	echo '<div class="alert-box alert">' . $err . '</div>';
}
?>
Login with the button on top right.
</div></div>
<?php
include("_footer.php");