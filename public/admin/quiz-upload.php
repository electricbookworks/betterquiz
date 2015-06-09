<?php
include_once("include.php");
$user = AdminUser::Get();
if (null==$user) {
	die("You need to be logged in");
}

header('Content-Type: text/plain; charset=utf-8');

try {   
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['upfile']['error']) ||
        is_array($_FILES['upfile']['error'])
    ) {
        throw new RuntimeException('Invalid parameters: ' . json_encode($_FILES['upfile']['error']));
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here.
    if ($_FILES['upfile']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    // $finfo = new finfo(FILEINFO_MIME_TYPE);
    // if (false === $ext = array_search(
    //     $finfo->file($_FILES['upfile']['tmp_name']),
    //     array(
    //         'bqf' => 'x-application/betterquiz',
    //     ),
    //     true
    // )) {
    //     throw new RuntimeException('Invalid file format.');
    // }
	$ext = pathinfo($_FILES['upfile']['name'], PATHINFO_EXTENSION);
	if ("bqf"!=$ext) {
		throw new RuntimeException('Invalid file format (need .bqf)');
	}

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
	$bqf = file_get_contents($_FILES['upfile']['tmp_name']);

	$quiz = BQQuiz::Parse($_FILES['upfile']['name'], $bqf);

    try {
        $quiz->SaveToDatabase($db);
        $res = array(
            "status"=>"ok",
            "message"=>"Successfully uploaded " . $_FILES['upfile']['name'],
            "content"=>$bqf,
            "json"=>$quiz
        );
    } catch (RuntimeException $e) {
        $res = array(
            "status"=>"failure",
            "error"=>$e->getMessage()
        );
    }
	echo json_encode($res);

} catch (Exception $e) {
	echo json_encode( array(
		"status"=>"error",
		"error"=>$e->getMessage()));
}
