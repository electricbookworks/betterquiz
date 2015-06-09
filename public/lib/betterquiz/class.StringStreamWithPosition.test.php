<?php

include_once("class.StringStreamWithPosition.php");


class StringStreamWithPositionTest extends \PHPUnit_Framework_TestCase {
	/**
	 * very basic test that ensures we've not messed up our stringstream functionality
	 */
	public function testStringStreamWithPosition() {
		if (bqf_supports_utf8()) {
			$stream = new StringStreamWithPosition("-","this日本語");
			$expect = array("t","h","i","s","日","本","語");

			$this->assertEquals("t", $stream->Peek());
					
			foreach ($expect as $e) {
				$this->assertEquals($e, $stream->Read());
				$stream->Unread();
				$this->assertEquals($e, $stream->Read());
			}
			$this->assertEquals($stream->Read(), FALSE);

			/**
			 * very basic test that ensures we've not messed up our stringstream functionality
			 */
			$stream = new StringStreamWithPosition("-","this日本語\nHi there\nFred");
			$expect = array("t","h","i","s","日","本","語","\n","H","i",' ','t','h','e','r','e',"\n", 'F','r','e','d',FALSE);
			$col =    array( 1,  2,  3,  4,  5,   6,   7,   0,  1,  2,  3,  4,  5,  6,  7,  8,  0,    1,  2,  3,  4,  4);
			$line =   array( 1,  1,  1,  1,  1,   1,   1,   2,  2,  2,  2,  2,  2,  2,  2,  2,  3,    3,  3,  3,  3,  3);
			for ($i=0; $i<count($expect); $i++) {
				$this->assertEquals($expect[$i], $stream->Read(), "i = $i");
				$this->assertEquals($col[$i], $stream->Col());
				$this->assertEquals($line[$i], $stream->Line());
				$stream->Unread();

				$this->assertEquals($expect[$i], $stream->Read(), "i = $i");
				$this->assertEquals($col[$i], $stream->Col());
				$this->assertEquals($line[$i], $stream->Line());

			}
		}
	}	
}