<?php

class ParserQuestion {
	public function __construct($quiz) {
		$this->_q = $quiz;
	}
	public function Receive($t, $v) {
		switch ($t) {
			case Tokens::EOF:
				return FALSE;
			case Tokens::ERR:
				throw new Exception($v);
			case Tokens::QUESTION:
				return new ParserOptions($this->_q->AddQuestion($v), $this);
		}
		throw new Exception("Unexpected token $v ($t) while parsing question");
	}
}