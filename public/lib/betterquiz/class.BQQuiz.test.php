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

	public function testZeroHandling() {
		$tests = array(
			array(<<<EOJS
{
	"meta": {"01":"0","0.2":"0.2","100":"100"},
	"questions": [
		{"question":"100% oxygen may damage the infant and therefore:",
		 "options": [
			 {"correct":false,"option":"Should never be given to any infant"},
			 {"correct":false, "option":"Should not be given for more than 24 hours"},
			 {"correct":true, "option":"Should only be given if a lower concentration of oxygen fails to correct central cyanosis"},
			 {"correct":false, "option":"Should never be given to a preterm infant"}
		 ]
		}
	]
}
EOJS
, <<<EOBQF
01: 0
0.2: 0.2
100: 100

100% oxygen may damage the infant and therefore:
- Should never be given to any infant
- Should not be given for more than 24 hours
+ Should only be given if a lower concentration of oxygen fails to correct central cyanosis
- Should never be given to a preterm infant
EOBQF
				), array(
<<<EOJS
{
	"meta": { "title":"Testing error when answer option is 0" },
	"questions": [
		{ "question": 

			"What is the normal total serum bilirubin concentration (TSB) in cord blood?",
		  "options": [
			{"correct":false,"option": "0 this zero creates a line break. On its own as an option, it throws an error"},
			{"correct":true, "option":"Less than 35 µmol/l"},
			{"correct":false, "option":"35–55 µmol/l"},
			{"correct":false, "option":"More than 55 µmol/l"}
		  ]
		},
		{ "question": "What is the normal total serum bilirubin concentration (TSB) in cord blood?",
		  "options": [
			{"correct":false, "option":"0 µmol/l"},
			{"correct":true, "option":"Less than 35 µmol/l"},
			{"correct":false,"option":"35–55 µmol/l"},
			{"correct":false,"option":"More than 55 µmol/l"}
		]}
	]
}
EOJS
, <<<EOBQF
title: Testing error when answer option is 0

What is the normal total serum bilirubin concentration (TSB) in cord blood?
-   0 this zero creates a line break. On its own as an option, it throws an error
+   Less than 35 µmol/l
-   35–55 µmol/l
-   More than 55 µmol/l

What is the normal total serum bilirubin concentration (TSB) in cord blood?
-   0 µmol/l
+   Less than 35 µmol/l
-   35–55 µmol/l
-   More than 55 µmol/l
EOBQF
));
		foreach ($tests as $t) {
			$jsQuiz = BQQuiz::ParseJson($t[0]);
			$quiz = BQQuiz::Parse("-", $t[1]);
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
		},
		{
			"question": "03 Check 0 handling",
			"options": [
				{"correct":false, "option":"0"},
				{"correct":true, "option":"0.13"},
				{"correct":false, "option":"0.1010"}
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

03 Check 0 handling
- 0
+ 0.13
- 0.1010

EOBQF
		);
		$quiz = BQQuiz::Parse("-", $tests[0]);
		$this->assertEquals($quiz, $jsQuiz);
	}
}