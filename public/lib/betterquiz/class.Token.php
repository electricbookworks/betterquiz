<?php
/**
 * Token wraps a particular token type and the value that is
 * relevant for that type (if the token type has an associated
 * value).
 */
class Token {
	var $_token;
	var $_value;

	public function __construct($token, $value) {
		$this->_token = $token;
		$this->_value = $value;
	}

	public function Token() {
		return $this->_token;
	}
	public function Value() {
		return $this->_value;
	}
	public function Equals($t) {
		return (($this->Token() == $t->Token()) && ($this->Value()==$t->Value()));
	}
	public function T() {
		return $this->Token();
	}
	public function V() {
		return $this->Value();
	}
}