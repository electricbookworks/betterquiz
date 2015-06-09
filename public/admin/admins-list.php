<?php
include_once("include.php");
$user = AdminUser::Secure();
$site = new AdminSite();

$pg = Utils::Param("pg", 0);
$itemsPerPage = 25;
$p = array(
	"limit_from" => ($pg * $itemsPerPage),
	"limit_to" => (($pg+1) * $itemsPerPage),
);
list ($itemsTotal, $items) = BQAdmin::Find($p);

include("_header.php");
include("_nav.php");

?>
<div class="row">
<div class="small-12 columns">

<table>
<thead><tr><th>Admin Email</th><th>&nbsp;</th></tr></thead>
<tbody>
<script>
function deleteAdmin(email) {
  if (confirm('Are you sure you want to delete the administrator ' + email + '?')) {
    document.location="admins-delete.php?email=" + encodeURIComponent(email);
    return true;
  }
  return false;
}

function createAdmin() {
  email = window.prompt('Enter the email address for the new Admin.');
  if (null!=email) {
    document.location='admins-create.php?email=' +
      encodeURIComponent(email);
    return true;
  }
  return false;
}
</script>
<?php
  foreach ($items as $a) {
  	?>
  	<tr class="admin-row">
  		<td><?php echo $a->Email(); ?></td>
      <td><a 
      onclick="return deleteAdmin('<?php echo $a->Email(); ?>');"
      ><i class="fa fa-trash"></i></a></td>
  	</tr>
  	<?php
  }
?>
</tbody>
</table>

<div>
<bq-paginator current="<?php echo $pg; ?>" id="pg1" pages-to-show="10" total-items="<?php echo $itemsTotal; ?>" items-per-page="<?php echo $itemsPerPage ?>"></bq-paginator>
<bq-param-paginator paginator="pg1" parameter="pg" />
</div>

<div>
<button onclick="return createAdmin();">New Admin</button>
</div>
<?php
include("_footer.php");
