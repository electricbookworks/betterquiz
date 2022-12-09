<?php
/**
 * @file
 * Display a single question to the user.
 * Query parameters:
 *  q - int - the question id
 *  o - int? - optional selection
 * css - string - optional css to include: handled in _header.php
 */

include_once("lib/include.php");

include("_header.php");

$qid = intval($_GET["q"]);
$examId = BQExam::GetSession();
$oid = BQExam::GetOptionForQuestion($db, $examId, $qid);

$q = BQQuestion::LoadQuestionById($db, $qid);
if (FALSE===$q) {
	die("FAILED TO LOAD QUESTION $qid");
}
// echo "QUESTION = <code><pre>" . json_encode($q) . "</pre></code>";
?>
<div class="question">
<form method="post" action="post_answer.php">
<input type="hidden" name="q" value="<?php echo $qid; ?>" />
<div class="question-text"><?php echo $q->QuestionHtml(); ?></div>
<?php
	Flash::Render();
?>
<div class="options">
<?php foreach ($q->Options() as $o) { ?>
	<div class="option">
		<input 
			type="radio" 
			name="o<?php echo $q->Id(); ?>" 
			value="<?php echo $o->Id(); ?>" 
			class="option-radio" 
			id="o<?php echo $o->Id(); ?>"
			<?php if ($oid==$o->Id()) { echo 'checked="checked"'; } ?>
			/>
		<label class="option-text" for="o<?php echo $o->Id(); ?>"><?php echo $o->OptionHtml(); ?></label>
	</div>
<?php
	} // end foreach $q->Options()
?>
</div>
<div class="question-navigate">
<?php
	$prevId = $q->PreviousQuestionId();
	if ($prevId) {
		?><a href="question.php?q=<?php echo $prevId; ?>">back</div><?php
	}
?>
<input type="submit" id="question-next" value="next" />
</button>
</div>
</form>
</div>
<?php
include("_footer.php");