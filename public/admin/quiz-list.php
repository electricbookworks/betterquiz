<?php
include_once("include.php");
$user = AdminUser::Secure();
$site = new AdminSite();

$pg = Utils::Param("pg", 0);
$itemsPerPage = 25;
$title = Utils::Param("title", "");
$p = array(
	"limit_from" => ($pg * $itemsPerPage),
	"limit_to" => (($pg+1) * $itemsPerPage),
	"title" => $title,
);
list ($itemsTotal, $items) = BQQuizList::Find($p);

include("_header.php");
include("_nav.php");

?>
<div class="row">
<div class="small-12 columns">
<div class="quizSearch">
<form method="get" action="quiz-list.php">
<input type="text" placeholder="title" name="title" value="<?php echo $title; ?>" />
<input type="hidden" name="pg" value="0" />
<!-- <input type="submit" name="search" /> -->
</form>
</div>

<table>
<thead><tr><th>ID</th><th>Title</th><th>bqf</th><th>Answers</th></tr></thead>
<tbody>
<?php
  foreach ($items as $q) {
  	?>
  	<tr class="quiz-row">
  		<td><?php echo $q->Id(); ?></td>
  		<td><?php echo $q->Title(); ?></td>
  		<td><a href="quiz-bqf.php?quiz_id=<?php echo $q->Id(); ?>">BQF</a></td>
  		<td><a href="quiz-answers.php?quiz_id=<?php echo $q->Id(); ?>"><?php echo $q->AnswerCount(); ?></a></td>
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
