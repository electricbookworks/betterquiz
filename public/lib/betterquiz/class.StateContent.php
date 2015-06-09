<?php

/**
 * StateContent parses a content value, emits the token passed in its
 * constructor with the content value, and continues onto the received next state.
 */ 
class StateContent {
	var $_nextState;
	var $_token;
	
	public function __construct($token, $nextState) {
		$this->_nextState = $nextState;
		$this->_token = $token;
	}
	public function Tokenize($stream) {
		$stream->Clear();
		$stream->ReadContentToEOL();
		$line = $stream->TrimString();
		if (0==bqf_strlen($line)) {
			$stream->Error("No Value found for content");
			return null;
		}
		$stream->Emit($this->_token);
		return $this->_nextState;
	}
}