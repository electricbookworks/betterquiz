<?php

include_once("class.StringStreamEmitter.php");
include_once("class.TokenArray.php");

class StringStreamEmitterTest extends \PhpUnit_Framework_TestCase {
	public function testStringStreamEmitter() {
		$bqf = "one: uno\ntwo: dos\n\nQuestion 1\n-Wrong\n+Right\n\nQuestion 2\n-Wrong2\n+'Right\n2'";
		$expect = array(
			new Token(Tokens::HEADER_KEY,"one"),
			new Token(Tokens::HEADER_VALUE, "uno"),
			new Token(Tokens::HEADER_KEY,"two"),
			new Token(Tokens::HEADER_VALUE, "dos"),
			new Token(Tokens::HEADERS_END, null),
			new Token(Tokens::QUESTION, "Question 1"),
			new Token(Tokens::OPTION_WRONG, "Wrong"),
			new Token(Tokens::OPTION_RIGHT, "Right"),
			new Token(Tokens::QUESTION, "Question 2"),
			new Token(Tokens::OPTION_WRONG, "Wrong2"),
			new Token(Tokens::OPTION_RIGHT, "'Right\n2'"),
			new Token(Tokens::EOF, null)
			);
		$toks = new TokenArray();
		$stream = new StringStreamEmitter("-", $bqf, $toks);
		$stream->Tokenize();

		$this->assertEquals(count($expect), $toks->Len());
		for ($i=0; $i<$toks->Len(); $i++) {
			$this->assertEquals($expect[$i]->T(), bqf_trim($toks->Token($i)->T()));
			$this->assertEquals($expect[$i]->V(), bqf_trim($toks->Token($i)->V()));
		}
	}
}