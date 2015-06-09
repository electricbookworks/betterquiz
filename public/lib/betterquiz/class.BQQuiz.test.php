<?php

include_once("class.BQQuiz.php");

class BQQuizTest extends \PHPUnit_Framework_TestCase {
	public function testBQQuiz() {
		$tests = array(
			array(	"one:uno\ntwo:dos\n---\n". 
					"Q1\n-Wrong1.1\n-Wrong1.2\n+Right1.3\n\n" .
					"Q2\n-Wrong2.1\n+Right2.2\n-Wrong2.3\n",
					<<<EOJS
{	
	"meta": { "one" : "uno", "two": "dos" },
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
			)
		);
		foreach ($tests as $test) {
			$quiz = BQQuiz::Parse("-", $test[0]);
			$jsQuiz = BQQuiz::ParseJSON($test[1]);
			$this->assertEquals($quiz, $jsQuiz);
		}
	}

	public function testBQFVariations() {
		$jsQuiz = BQQuiz::ParseJSON(<<<EOJS
{	
	"meta": { "one" : "uno", "two": "dos" },
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
		);
		$tests = array(
<<<EOBQF
one: uno
two: dos

Q1
- Wrong1.1  
- Wrong1.2  
+ Right1.3  



Q2
- Wrong2.1
+   Right2.2
  - Wrong2.3
EOBQF
		);
		foreach ($tests as $bqf) {
			$quiz = BQQuiz::Parse("-", $bqf);
			$this->assertEquals($quiz, $jsQuiz);
		}
	}
}