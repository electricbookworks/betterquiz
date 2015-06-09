<?php

include_once("bqf_strings.php");

/** 
 * StringStream contains a string an permits the user to read it one character at a time, and to
 * unread a character as we go.
 */
class StringStream {
	var $_data;
	var $_i;
	var $_len;
	var $_eof;

	public function __construct($data) {
		$this->_data = $data;
		$this->_i = 0;
		$this->_len = bqf_strlen($data);
		$this->_eof = false;
	}

	/**
	 * RemainingString returns the data that hasn't yet been streamed.
	 */
	public function RemainingString() {
		return bqf_substr($this->_data, $this->_i);
	}

	/** 
	 * Read returns the next character from the stream, or FALSE if
	 * all the characters have been read from the stream.
	 */
	public function Read() {
		if ($this->_i < $this->_len) {
			$c = bqf_substr($this->_data, $this->_i++, 1);
			return $c;
		}
		// If you read an EOF, then Unread does nothing
		$this->_eof = true;
		return FALSE;
	}

	/**
	 * Peek returns the next character that Read will read. Multiple calls to 
	 * Peek will return the same character over and over.
	 */
	public function Peek() {
		if ($this->_i < $this->_len) {
			return bqf_substr($this->_data, $this->_i, 1);
		}
		return FALSE;
	}

	/**
	 * Unread unreads the last character read from the stream.
	 */
	public function Unread() {
		if ((!$this->_eof) && (0<$this->_i)) {
			$this->_i--;
			return true;
		}
		return false;
	}

	/**
	 * True if EOF has been read
	 */
	public function Eof() {
		return $this->_eof;
	}
}