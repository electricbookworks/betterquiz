<?php

class ParserHeader {
	public function __construct($quiz) {
		$this->_q = $quiz;
	}
	public function Receive($t, $v) {
		switch ($t) {
			case Tokens::EOF:
				return;
			case Tokens::ERR:
				throw new Exception($v);
			case Tokens::HEADER_KEY:
				return new ParserHeaderValue($this->_q, $v, $this);
			case Tokens::HEADERS_END:
				return new ParserQuestion($this->_q);
		}
		throw new Exception("Unexpected token $v ($t) while parsing Header");
	}
}
