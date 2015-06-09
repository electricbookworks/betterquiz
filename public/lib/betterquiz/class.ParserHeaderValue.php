<?php

class ParserHeaderValue {
	public function __construct($quiz, $v, $next) {
		$this->_q = $quiz;
		$this->_key = $v;
		$this->_next = $next;
	}
	public function Receive($t, $v) {
		switch ($t) {
			case Tokens::EOF:
				throw new Exception("Unexpected EOF while expecting header value for " . $this->_key);
			case Tokens::ERR:
				throw new Exception($v);
			case Tokens::HEADER_VALUE:
				$this->_q->AddMeta($this->_key, $v);
				return $this->_next;
		}
		throw new Exception("Unexpected token $v ($t) while parsing Header Value");
	}
}
