<?php

class StateOption {
	var $_nextState;
	
	public function __construct($nextState) {
		$this->_nextState = $nextState;
	}
	public function Tokenize($stream) {
		// we always start on a new line because we read to EOL for question and options
		$stream->Clear();
		// An empty line takes us right back to questions
		if ($stream->SkipWsToEOL()) {
			return $this->_nextState;	// Back to questions
		}

		$c = $stream->Read();
		switch ($c) {
			case FALSE:
				$stream->EmitEOF();
				return FALSE;
			case ".":
				// fallthrough
			case "-":
				//fallthrough
			case "+":
				return new StateContent(
					("+"==$c) ? Tokens::OPTION_RIGHT : Tokens::OPTION_WRONG,
				    $this);
			default:
				// If the line doesn't start with a plus, a - or a ., we presume we're on the 
				// next question.
				$stream->Unread();
				return $this->_nextState;
		}
		$stream->Error("Unabled to parse option value for last question");
	}
}
