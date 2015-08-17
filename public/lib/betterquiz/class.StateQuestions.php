<?php

class StateQuestions {
	public function Tokenize($stream) {
		$stream->SkipWs();
		$stream->Clear();
		$c = $stream->Read();
		if (FALSE===$c) {
			$stream->EmitEOF();
			return null;
		}
		switch ($c) {
			// We handle FALSE in a separate case to manage 
			// === comparison
			// case FALSE:
			// $stream->EmitEOF();
			// return null;
			case "+":
				// fallthrough
			case "-":
				// fallthrough
			case ".":
				$stream->Error("Expecting a question but got option");
		}
		// cannot be ws since we skipped ws above
		$question = $stream->ReadContentToEOL();
		$stream->Emit(Tokens::QUESTION);
		return new StateOption($this);
	}
}
