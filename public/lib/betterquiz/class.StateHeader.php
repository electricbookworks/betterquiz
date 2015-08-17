<?php

/**
 * StateHeader reads the header for the BQF. A Header is of the form
 * Key: Value
 * The Header is ended by either a blank line, or a "---" header termination line.
 * The Value can be a string, to EOL, or XML
 */
class StateHeader {
	public function Tokenize($stream) {
		$stream->Clear();
		$stream->ReadUntilAny(":\n");
		$c = $stream->Peek();
		// EOF
		if (FALSE===$c) {
			$line = $stream->TrimString();
			if (0<bqf_strlen($line)) {
				$stream->Error("Unexpected EOF with header line");
				return FALSE;
			}
			$stream->EmitEOF();
			return FALSE;			
		}
		switch ($c) {
			// FALSE case handled separately above to handle ===
			// comparison
			//case FALSE:
			case "\n":
				$line = $stream->TrimString();
				if ((""==$line) || ("---"==$line)) {
					$stream->Emit(Tokens::HEADERS_END);
					return new StateQuestions();
				}
				$stream->SendError("Unexpected Header line without key-value set");
				return FALSE;
			case ":":
				$line = $stream->TrimString();
				$stream->Emit(Tokens::HEADER_KEY);
				$stream->Skip();	// Skip over the : character
				return new StateContent(Tokens::HEADER_VALUE,$this);
			default:
				assert("Should never get here");
		}
	}
}