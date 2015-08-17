<?php

include_once("class.StringStreamBuffered.php");

class StringStreamBufferedTest extends \PHPUnit_Framework_TestCase {
	public function testStringStreamBuffered() {
		$stream = new StringStreamBuffered("-","This is a test\nAnd a second line\n");
		$stream->ReadUntil(function($c) { return $c===' '; });
		$this->assertEquals("This", $stream->String());
		$this->assertEquals(" ", $stream->Peek());
	}

	public function testSkipWs() {
		$stream = new StringStreamBuffered("-",
			"This is a test\nand a second line\n0");
		$expect = array("This","is","a","test","and","a","second","line","0");
		foreach ($expect as $e) {
			$stream->SkipWs();
			$stream->ReadUntilWs();
			$g = $stream->String();
			$this->assertEquals($e, $g);
		}
	}

	public function testReadUntilAny() {
		$stream = new StringStreamBuffered("-", "123456abc99");
		$params = array("a","9","-");
		$expect = array("123456","abc","99");
		for ($i=0; $i<count($params); $i++) {
			$stream->Clear();
			$stream->ReadUntilAny($params[$i]);
			$this->assertEquals($expect[$i], $stream->String());
		}
	}
	public function testTrimString() {
		$stream = new StringStreamBuffered("-", "   this is a \n   test    \n");
		$expect = array("this is a","test");
		foreach ($expect as $e) {
			$stream->Clear();
			$stream->ReadToEOL();
			$this->assertEquals($e, $stream->TrimString());
		}
	}

	public function testReadWs() {
		$stream = new StringStreamBuffered("-", "   one  ");
		$stream->Clear();
		$stream->ReadWs();
		$this->assertEquals("   ", $stream->String());
		$c = $stream->Read();
		$this->assertEquals("o", $c);
	}

	public function testReadTokenOrString() {
		$stream = new StringStreamBuffered("-", "'test'");
		$stream->Clear();
		$stream->ReadTokenOrString();
		$this->assertEquals("'test'", "'test'");

		$stream = new StringStreamBuffered("-", "one `two three` \"four \\\" five\" roku 'six'");
		$expects = array("one", " `two three`", ' "four \" five"',  " roku", " 'six'" );
		foreach ($expects as $e) {
			$stream->Clear();
			try {
				$stream->ReadTokenOrString();
			} catch (Exception $e) {
				$this->assertEquals(1,2);
			}
			$this->assertEquals($e, $stream->String());
		}
	}

	public function test_readAttributes() {
		$tests = array(
			array( 'one="1" two="dos y tres" three="fred">', array('one="1" two="dos y tres" three="fred"'))
		);
		foreach ($tests as $test) {
			$stream = new StringStreamBuffered("-", $test[0]);
			foreach ($test[1] as $expect) {
				$stream->ReadAttributes();
				$this->assertEquals($expect, $stream->String());
				$stream->Clear();
			}
		}
	}

	public function test_ReadXMLTagOrEOLOrString() {
		$tests = array(
			array( 'this is a test', array('this is a test')),
			array('<tag name="one">The content</tag>', array('<tag name="one">The content</tag>')),
			array('<tag name="two">Two<tag name="three">3</tag></tag>More', 
				array('<tag name="two">Two<tag name="three">3</tag></tag>', 'More')	),
			array('<b>Test of <br /> quick close\n and line breaks</b>test',
				array('<b>Test of <br /> quick close\n and line breaks</b>' ,'test')),
			array("<b>xml</b>\"a string\"<c>xml</c>and to eol\n<d>DDD</d>",
				array('<b>xml</b>' , '"a string"', '<c>xml</c>', "and to eol\n", '<d>DDD</d>'))
		);
		foreach ($tests as $test) {
			$stream = new StringStreamBuffered("-", $test[0]);
			foreach ($test[1] as $expect) {
				$stream->ReadXMLTagOrEOLOrString();
				$this->assertEquals($expect, $stream->String());
				$stream->Clear();
			}
		}
	}

	public function test_SkipWsToEOL() {
		$tests = array(
			array("     \nt", TRUE, "t"),
			array("\n", TRUE, FALSE),
			array("  D  \n-", FALSE, "D"),
			array(" fred", FALSE, "f"),
			array("0\n", FALSE, "0")
		);
		foreach ($tests as $test) {
			$stream = new StringStreamBuffered("-", $test[0]);
			$this->assertEquals($test[1], $stream->SkipWsToEOL());
			$this->assertEquals($test[2], $stream->Read());
		}
	}

	public function test_ReadContentToEOL() {
		$data =  <<<EOSTR
one
dos
'two

'      
<b>a multi-line
tag <a>a</a></b>    
"fred"
EOSTR
		;
		$stream = new StringStreamBuffered("-",$data);

		$expects = array("one", "dos", "'two\n\n'", "<b>a multi-line\ntag <a>a</a></b>", '"fred"');
		foreach ($expects as $expect) {
			$stream->ReadContentToEOL();
			// print("Read '" . $stream->TrimString() . "', Remaining : ". $stream->RemainingString() . "\n");
			$this->assertEquals($expect, $stream->TrimString());
			$stream->Clear();
		}
	}
}