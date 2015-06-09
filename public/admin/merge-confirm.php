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

$fromUser = BQUser::LoadUserById($db, $fromId);
$toUser = BQUser::LoadUserById($db, $toId);


function renderError($msg) {
	if (!$msg) return;
	echo <<<EOHTML
<div data-alert class="alert-box alert">$msg</div>
EOHTML
	;
}
function checkUserNotMerged($user) {
	if (!$user->IsMerged()) {
		return false;
	}
	return "This user has already been merged with " . 
		$user->GetActualUser()->EmailOrMobile() . ". " .
		" Please use that account.";
}

?>
<div class="row">
<div class="small-12 columns">
<div class="userMerge">
<h1>Confirm Merge Users</h1>
<p>You are about to merge the user:</p>
<p class="userDetail userFrom"><?php echo $fromUser->Fullname(); ?> - <?php echo $from; ?></p>
<p>with the user:</p>
<p class="userDetail userTo"><?php echo $toUser->Fullname(); ?> - <?php echo $to; ?></p>
<p>This cannot be undone. Press CONFIRM to continue.</p>
<form method="post" action="merge-do.php">
<input type="hidden" name="process" value="1" />
<input type="hidden" name="fromId" value="<?php echo $fromId; ?>" />
<input type="hidden" name="toId" value="<?php echo $toId; ?>" />
<input type="hidden" name="from" value="<?php echo $from; ?>" />
<input type="hidden" name="to" value="<?php echo $to; ?>" />
<a  class="button" href="merge-form.php?from=<?php
 echo urlencode($from); 
?>&to=<?php 
 echo urlencode($to);?>">Cancel</a>
<input type="submit" class="button" value="CONFIRM" />
</form>

</div>

</div></div>

<?php
include("_footer.php");
