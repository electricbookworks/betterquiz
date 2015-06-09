<?php

include_once("class.StringStream.php");

/**
 * StringStreamWithPosition implements a StringStream that keeps track of line and col position in a
 * file. It provides this information for debugging purposes.
 */
class StringStreamWithPosition extends StringStream {
	var $_filename;
	var $_col;
	var $_lastcol;
	var $_line;

	public function __construct($filename, $data) {
		parent::__construct($data);
		$this->_filename = $filename;
		$this->_col = 0;
		$this->_line = 1;
		$this->_lastcol = -1;
	}

	public function Read() {
		$c = parent::Read();
		if (FALSE===$c) {
			return FALSE;
		}

		if ("\n"==$c) {
			$this->_lastcol = $this->_col;
			$this->_col = 0;
			$this->_line++;
			// echo "Read NEWLINE, col = $this->_col, line = $this->_line\n";
		} else {
			$this->_col++;
			// echo "Read $c, col = $this->_col, line = $this->_line\n";
		}
		return $c;
	}

	public function Unread() {
		if (!parent::Unread()) {
			return false;
		}

		if (0==$this->_col) {
			$this->_col = $this->_lastcol;
			$this->_line--;
		} else {
			$this->_col--;
		}
		return true;
	}

	public function Col() {
		return $this->_col;
	}

	public function Line() {
		return $this->_line;
	}
}