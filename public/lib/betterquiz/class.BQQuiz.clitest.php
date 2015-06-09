<?php

include_once("class.BQQuiz.php");

$json = <<<EOJS
{	"meta": { "one" : "uno", "two": "dos" },
	"questions" : [
		{
			"question": "Q1",
			"options": [
				{"correct":false, "option":"Wrong1.1" },
				{"correct":false, "option":"Wrong1.2" },
				{"correct":true,  "option":"Right1.3" }
			]
		},
		{
			"question": "Q2",
			"options": [
				{"correct":false, "option":"Wrong2.1"},
				{"correct":true, "option":"Right2.2"},
				{"correct":false, "option":"Wrong2.3"}
			]
		}
	]
}
EOJS
;

//$json= preg_replace('/\n/','',$json);
// $q = BQQuiz::ParseJSON($json);
$js = json_decode($json);
print_r($json);
print("\n");
var_dump($js);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }