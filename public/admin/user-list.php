<?php
include_once("include.php");
$user = AdminUser::Secure();
$site = new AdminSite();

$pg = Utils::Param("pg", 0);
$itemsPerPage = 25;
$search = Utils::Param("search", "");
$p = array(
	"limit_from" => ($pg * $itemsPerPage),
	"limit_to" => (($pg+1) * $itemsPerPage),
	"search" => $search,
);
list ($itemsTotal, $items) = BQUserListItem::Find($p);

include("_header.php");
include("_nav.php");

?>
<div class="row">
<div class="small-12 columns">
<div class="quizSearch">
<form method="get" action="user-list.php">
<input type="text" placeholder="search" name="search" value="<?php echo $search; ?>" />
<input type="hidden" name="pg" value="0" />
<!-- <input type="submit" name="search" /> -->
</form>
</div>

<table>
<thead><tr><th>ID</th><th>Full name</th><th>Mobile</th><th>Email</th><th>Answers</th></tr></thead>
<tbody>
<?php
  foreach ($items as $u) {
  	?>
  	<tr class="quiz-row">
  		<td><?php echo $u->Id(); ?></td>
  		<td><?php echo $u->Fullname(); ?></td>
      <td><?php echo $u->Mobile(); ?></td>
      <td><?php echo $u->Email(); ?></td>
      <td><a href="user-answers.php?user_id=<?php echo $u->Id(); ?>"><?php echo $u->ExamCount(); ?></a></td>
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

<?php
include("_footer.php");
