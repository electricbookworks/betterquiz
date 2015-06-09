<?php

class ParserOptions {
	public function __construct($question, $next) {
		$this->_question = $question;
		$this->_next = $next;
	}
	public function Receive($t, $v) {
		switch ($t) {
			case Tokens::OPTION_RIGHT:
				// fallthrough
			case Tokens::OPTION_WRONG:
				$this->_question->AddOption($t==Tokens::OPTION_RIGHT, $v);
				return $this;
		}
		// Any other token we parse as a question token
		return $this->_next->Receive($t, $v);
	}
}