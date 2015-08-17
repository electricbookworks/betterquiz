<?php

include_once("class.StringStreamWithPosition.php");

/**
 * A buffered StringStream
 */
class StringStreamBuffered {
	const TOKEN_CHARS = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_-$#^!~.";

	var $_current;
	var $_stream;

	public function __construct($filename, $data) {
		$this->_stream = new StringStreamWithPosition($filename, $data);
		$this->_current = array();
	}

	public function RemainingString() {
		return $this->_stream->RemainingString();
	}

	public function String() {
		return implode($this->_current);
	}
	public function TrimString() {
		return bqf_trim($this->String());
	}

	public function Clear() {
		$this->_current = array();
	}

	public function Read() {
		$c = $this->_stream->Read();
		if (FALSE!==$c) {
			array_push($this->_current, $c);
		}
		return $c;
	}

	public function Peek() {
		return $this->_stream->Peek();
	}

	public function Skip() {
		return $this->_stream->Read();
	}

	public function Unread() {
		if ($this->_stream->Unread()) {
			if (0 < count($this->_current)) {
				$this->_current = array_slice($this->_current, 0, count($this->_current)-1);
				return true;
			}
			return true;
		}
		return false;
	}

	public function ReadUntil($fn) {
		do {
			$c=$this->Read();
		} while ((FALSE!==$c) && (!$fn($c)));
		$this->Unread();
	}

	public function ReadUntilAny($chars) {
		do {
			$c = $this->Read();
			if (FALSE===$c) break;
		} while (FALSE === bqf_strpos($chars, $c));
		$this->Unread();
	}

	public function ReadAny($chars) {
		do {
			$c = $this->Read();
			if (FALSE!==$c) break;
		} while (FALSE !== bqf_strpos($chars, $c));
		$this->Unread();
	}

	public function ReadWhile($fn) {
		do {
			$c = $this->Read();
		} while ((FALSE!==$c) && ($fn($c)));
		$this->Unread();
	}

	public function ReadTo($fn) {
		do {
			$c = $this->Read();
		} while ((FALSE!==$c) && (!$fn($c)));
	}

	public function ReadToEOL() {
		do {
			$c= $this->Read();
		} while ((FALSE!==$c) && ("\n"!==$c));
	}

	public function SkipWs() {
		$this->ReadWhile(function($c) { return bqf_isspace($c); });
		$this->Clear();
	}

	public function ReadWs() {
		$this->ReadWhile(function($c) { return bqf_isspace($c); });
	}

	/**
	 * SkipWsToEOL will skip all whitespace to the first EOL encountered.
	 * It will ALSO skip the EOL.
	 * It returns TRUE if it gets to EOL or EOF without encountering any non-WS characters,
	 * and FALSE if it encounters a non-WS character before EOL.
	 * It leaves the next character the first non-WS character encountered,
	 * and the String() empty.
	 */
	public function SkipWsToEOL() {
		while (true) {
			$c = $this->Read();
			if ((FALSE===$c) || ("\n"==$c)) {
				return TRUE;
			}
			if (!bqf_isspace($c)) {
				$this->Unread();
				return FALSE;
			}
		}
	}

	public function ReadUntilWs() {
		return $this->ReadUntil(function($c) { return bqf_isspace($c); });
	}

	/**
	 * ReadTokenOrString will read from the current position to across
	 * whitespace, to the End of String, which
	 * is defined as either the string that starts now, or the token.
	 * The string is delimited by the first ", ` or ' encountered, and escaped with \ inside
	 * the string.
	 */
	public function ReadTokenOrString() {
		$this->ReadWs();
		$c = $this->Read();

		if (FALSE===$c) {
			return;
		}
		switch ($c) {
			// FALSE handled in separate if above for === comparison
			case "\"":
			case "'":
			case "`":
				return $this->readStringDelimited($c);
			default:
				// Ensure that we don't read a non-token character, so let ReadToken handle the
				// token reading
				$this->Unread();
				return $this->ReadToken();
		}
	}


	public function ReadToken() {
		return $this->ReadWhile(function($c) {
			return (FALSE!==bqf_strpos(StringStreamBuffered::TOKEN_CHARS, $c));
		});
	}

	/**
	 * ReadXMLTagOrEOL reads an entire XMLTag, or if an XML tag is not encountered, it reads to EOL.
	 */
	public function ReadXMLTagOrEOLOrString() {
		$this->ReadWs();
		$c = $this->Read();
		if (FALSE===$c) {
			return;
		}
		switch ($c) {
			// FALSE handled in separate IF above to permit === comparison
			// case FALSE:
			// 	return;
			case "<";
				$this->readOpenTag();
				break;
			case "'":
				// fallthrough
			case "\"":
				// fallthrough
			case "`":
				$this->Unread();
				$this->ReadTokenOrString();
				break;
			default:
				$this->ReadToEOL();
		}
	}

	/**
	 * ReadContentToEOL reads a content item - a string or XML element, or just to EOL.
	 * It also reads to the EOL and returns TRUE if EOL was passed, or FALSE
	 * if a non-EOL element was encountered after the content, but before EOL.
	 */ 
	public function ReadContentToEOL() {
		$this->ReadWs();
		$c = $this->Read();
		// Catch this for LOGIC match, otherwise PHP matches '0' to FALSE
		if (FALSE===$c) {
			return true;
		}
		switch ($c) {
			// Catch ABOVE with === match
			// case FALSE:
			// 	return TRUE;
			case "<";
				$this->readOpenTag();
				return $this->SkipWsToEOL();
			case "'":
				// fallthrough
			case "\"":
				// fallthrough
				// fallthrough
			case "`":
				$this->Unread();
				$this->ReadTokenOrString();
				return $this->SkipWsToEOL();
			default:
				//print("About to ReadToEOL. Remianing: " . $this->RemainingString() . "\n");
				$this->ReadToEOL();		
				return TRUE;	
		}
	}

	protected function readOpenTag() {
		$this->readToken();
		$tag = $this->TrimString();
		$this->readAttributes();
		$c = $this->Peek();
		if (FALSE===$c) {
				throw new Exception("Unexpected EOF encountered while reading tag $tag");			
		}
		switch ($c) {
			// FALSE handled in separate IF above for === comparisons
			// case FALSE:
			case "/":
				$this->Read();
				$c = $this->Read();
				if (">"!=$c) {
					throw new Exception("Bad tag closure for tag $tag");
				}
				return;	// Tag is closed
			case ">":
				return $this->readTagInternals($tag);
		}
	}

	protected function readTagInternals($tag) {
		while (true) {
			$this->ReadUntilAny("<");
			$this->Read();	// Read the opening <
			$c = $this->Read();	// Next character
			if (FALSE===$c) {
				throw new Exception("Unexpected EOF encountered while reading tag $tag");
			}
			switch ($c) {
				// FALSE handled in separate IF above to allow === comparison
				// case FALSE:
				case "/":
					// We're not bothering to check that the tag is closed properly
					// although we could implement a 'readExact("/$tag")' for the Stream
					$this->ReadUntilAny(">");
					$c = $this->Read();	// Read the >
					if (FALSE===$c) {
						return new Exception("Unexpected EOF encountered while closing tag $tag");
					}
					return;
				default:	// Opening of a new tag
					$this->readOpenTag();	// Recurse to read the new tag we've encountered
			}
		}
	}

	public function readAttributes() {
		$this->ReadWs();
		$c = $this->Peek();
		if (FALSE===$c) {
			throw new Exception("Unexpected EOF while trying to read attribute at line " . 
					$this->Line() . ", col ", $this->Col());			
		}
		switch ($c) {
		// FALSE handled in separate IF above to allow === comparison
		// case FALSE:

		case "/":
			// fallthrough
		case ">":
			return;
		}
		// Read the attribute, then seek an =, then potentially read an attribute
		$this->ReadToken();
		$this->ReadWs();
		$c = $this->Peek();
		if ("="==$c) {
			$this->Read();	// Read the = sign
			$this->ReadTokenOrString();
		}
		return $this->readAttributes();
	}

	protected function readStringDelimited($delimit) {
		$escaped = false;
		$startCol = $this->Col();
		$startLine = $this->Line();
		while(true) {
			$c = $this->Read();
			if (FALSE===$c) {
				throw new Exception("Unexpected EOF while parsing delimited string from line $startLine, col $startCol");
				// ERROR - EOF with unterminated string
			}
			if ($escaped) {
				$escaped = false;
				continue;
			}
			if ("\\"==$c) {
				$escaped = true;
				continue;
			}
			if ($delimit==$c) {
				return TRUE;	// Successfully read the string
			}
		};
	}

	public function Col() {
		return $this->_stream->Col();
	}
	public function Line() {
		return $this->_stream->Line();
	}
}