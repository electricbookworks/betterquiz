<?php

include_once("class.StringStream.php");


class StringStreamTest extends \PHPUnit_Framework_TestCase {
	public function testStringStream() {
		if (bqf_supports_unicode()) {
			$stream = new StringStream("this日本語");
			$expect = array("t","h","i","s","日","本","語");
			$this->assertEquals("t", $stream->Peek());
			foreach ($expect as $e) {
				$this->assertEquals($e, $stream->Read());
				$stream->Unread();
				$this->assertEquals($e, $stream->Read());
			}
			$this->assertEquals($stream->Read(), FALSE);
		}
	}

	public function testStringStreamWithHEREDOC() {
		$data = <<<HDOC
one
HDOC;
		$this->assertEquals($data, "one");
		$s = new StringStream($data);
		$expect = array("o","n","e",FALSE);
		foreach ($expect as $e) {
			$this->assertEquals($e, $s->Read());
		}
	}
	
	public function testStringStreamRemaining() {
		$data = "one";
		$s = new StringStream($data);
		$expect = array("one","ne","e");
		foreach ($expect as $e) {
			$this->assertEquals($e, $s->RemainingString());
		}
	}
}