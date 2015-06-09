<?php

include_once("bqf_strings.php");

class bqf_unescape_stringTest extends PHPUnit_Framework_TestCase {
	public function testUnescape() {
		$tests = array(
			array("one", "one"),
			array('<a href="test">test</a>', '<a href="test">test</a>'),
			array('  with spaces  ', '  with spaces  '),
			array('  "quoted"', 'quoted'),
			array("   `it's a comment`  ", "it's a comment"),
			array('   "a new\nline"  ', "a new\nline"),
			array('   "delimited \" in string"', "delimited \" in string"),
			array('   "cross \\\' delimit"', "cross \\' delimit")
		);
		foreach ($tests as $test) {
			$this->assertEquals(bqf_unescape_string($test[0]), $test[1]);
		}
	}
}