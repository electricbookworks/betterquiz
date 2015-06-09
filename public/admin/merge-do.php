<?php
include_once("include.php");
$user = AdminUser::Secure();
$site = new AdminSite();

include("_header.php");
include("_nav.php");

$from = Utils::Param("from");
$to = Utils::Param("to");
$fromId = Utils::Param("fromId", 0);
$toId = Utils::Param("toId", 0);
$fromUser = BQUser::LoadUserById(null, $fromId);
$toUser = BQUser::LoadUserById(null, $toId);

BQUser::MergeUsers($fromId, $toId);

?>
<div class="row">
<div class="small-12 columns">
<div class="userMerge">
<h1>Merged Users</h1>
<p>We have successfully merged the user:
<p class="userDetail userFrom"><?php echo $fromUser->Fullname(); ?> - <?php echo $from; ?></p>
<p>with the user:</p>
<p class="userDetail userTo"><?php echo $toUser->Fullname(); ?> - <?php echo $to; ?></p>
<a class="button" href="merge-form.php">Merge Another</a>

</div>

</div></div>

<?php
include("_footer.php");
