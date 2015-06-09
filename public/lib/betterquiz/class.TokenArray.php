<?php

include_once("class.Token.php");

class TokenArray {
	var $_tokens;

	public function __construct() {
		$this->_tokens = array();
	}

	public function Receive($t, $v) {
		array_push($this->_tokens, new Token($t, $v));
	}

	public function Len() {
		return count($this->_tokens);
	}

	public function Token($i) {
		return $this->_tokens[$i];
	}
}