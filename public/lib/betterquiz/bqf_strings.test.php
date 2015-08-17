<?php

include_once("bqf_strings.php");

class bqf_stringTest extends PHPUnit_Framework_TestCase {
	public function testIsspace() {
		$tests = array(
			array(" ", true),
			array("x", false),
			array("0", false),
			array(0, false),
		);
		foreach ($tests as $test) {
			$this->assertEquals(bqf_isspace($test[0]), $test[1]);
		}
	}
}