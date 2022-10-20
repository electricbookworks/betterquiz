<?php
global $user;
if (isset($excludeNav) && $excludeNav) return;

$self = urlencode($_SERVER['REQUEST_URI']);
?>
<div class="nav">
<div class="link exit"><a href="exit.php?src=<?php echo $self; ?>">exit</a></div>
<div class="link profile"><a href="register_form.php?src=<?php echo $self; ?>&uid=<?php echo BQUser::GetSession(); ?>">profile</a></div>
</div>
