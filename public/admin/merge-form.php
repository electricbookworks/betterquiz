<?php
include_once("include.php");
$user = AdminUser::Secure();
$site = new AdminSite();

include("_header.php");
include("_nav.php");

$process = Utils::Param("process", false);
$fromAccount = Utils::Param("from","");
$toAccount = Utils::Param("to","");

$generalError = false;
$fromError = false;
$toError = false;

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

if ($process) {
	$fromUser = BQUser::LoadUserByEmailOrMobile(null, $fromAccount);
	$toUser = BQUser::LoadUserByEmailOrMobile(null, $toAccount);
	if (!$fromUser) {
		$fromError = "Sorry, I can't find that user.";
	}
	if (!$toUser) {
		$toError = "Sorry, I can't find that user.";
	}
	if ($fromUser->Id()== $toUser->Id()) {
		$toUser = null;
		$generalError = "The user $fromAccount and $toAccount are the same user.";
	}
	if ($fromUser && $toUser) {
		$fromError = checkUserNotMerged($fromUser);
		$toError = checkUserNotMerged($toUser);
		if (!($fromError || $toError)) {
			Utils::Redirect("merge-confirm.php",
				array("from"=>$fromAccount, "to"=>$toAccount,
					"fromId"=>$fromUser->Id(),
					"toId"=>$toUser->Id()));
		}
	}
}
?>
<div class="row">
<div class="small-12 columns">
<div class="userMerge">
<h1>Merge Users</h1>
<p>This feature allows you to merge two user accounts.
The <em>FROM</em> account will have all its quizzes reassigned to the 
<em>TO</em> account, and the user will in future have to login with the
<em>TO</em> account.
</p>
<?php
	renderError($generalError);
?>
<form method="post" action="merge-form.php">
<input type="hidden" name="process" value="1" />
<label for="from">FROM email or mobile</label>
<?php
	renderError($fromError);
?>
<input type="text" name="from" id="from" 
value="<?php echo $fromAccount; ?>" />
<?php
	renderError($toError);
?>
<label for="to">TO email or mobile</label>
<input type="text" name="to" id="to"
value="<?php echo $toAccount; ?>" />
<input type="submit" class="button" value="MERGE" />
</form>

</div>

</div></div>

<?php
include("_footer.php");
