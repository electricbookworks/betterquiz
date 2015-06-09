<?php

include_once("class.Tokens.php");

include_once("class.StateHeader.php");
include_once("class.StateOption.php");
include_once("class.StateQuestions.php");
include_once("class.StateContent.php");

include_once("class.StringStreamBuffered.php");

class StringStreamEmitter extends StringStreamBuffered {
	public function __construct($filename, $data, $tokenReceiver) {
		parent::__construct($filename, $data);
		$this->_receiver = $tokenReceiver;
	}

	public function Error($message) {
		$this->_receiver->Receive(Tokens::ERR, "$message at $filename:" . $this->_stream->Line() . ":" . $this->_stream->Col());
	}
	public function Emit($tok) {
		$this->_receiver->Receive($tok, $this->String());
	}
	public function EmitEOF() {
		$this->_receiver->Receive(Tokens::EOF, null);
	}
	public function Tokenize() {
		$state = new StateHeader();
		while ($state) {
			$state = $state->Tokenize($this);
		}
	}
}