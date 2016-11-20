<?php

/**
 * ErrorMessage is a utility class for returning errors from functions.
 */
class ErrorMessage {
	public function __construct($msg, $file='', $line=0) {
		error_log("ERROR $file:$line: " . $msg);
		$this->msg = $msg;
		$this->file = $file;
		$this->line = $line;
	}
	/**
	 * IsError returns true if the passed object/variable is
	 * an Error.
	 */
	public static function IsError($o) {
		if (!is_object($o)) {
			return false;
		}
		return ($o instanceof ErrorMessage);
	}
	/**
	 * Message returns the error message.
	 */
	public function Message() {
		return $this->msg;
	}
}