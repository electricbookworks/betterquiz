<?php

include_once("class.ParserHeader.php");
include_once("class.ParserHeaderValue.php");
include_once("class.ParserOptions.php");
include_once("class.ParserQuestion.php");

class ParserTokenReceiver {
	public function __construct($quiz) {
		$this->_state = new ParserHeader($quiz);
	}
	public function Receive($t, $v) {
		if (FALSE==$this->_state) {
			throw new Exception("Received token $v ($t) but no suitable Parser to handle it");
		}
		$this->_state = $this->_state->Receive($t, $v);
	}
}
