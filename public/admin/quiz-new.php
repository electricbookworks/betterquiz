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
<div class="small-12 columns">
<form action="quiz-upload.php" id="upload" class="dropzone"></form>
</div></div>
<script type="text/javascript">
Dropzone.options.upload = {
	  url: "quiz-upload.php",
	  paramName: "upfile", // The name that will be used to transfer the file
	  maxFilesize: 2, // MB
	  accept: function(file, done) {
	    if (file.name == "justinbieber.jpg") {
	      done("Naha, you don't.");
	    }
	    else { done(); }
	  },
	  init: function() {
	  	this.on('error', function(file, errorMessage) {
	  		alert('error'+ errorMessage);
	  	});
	  	this.on('success', function(file, response) {
	  		alert(response);
	  	});
	  }
};
</script>
<?php
include_once("_footer.php");