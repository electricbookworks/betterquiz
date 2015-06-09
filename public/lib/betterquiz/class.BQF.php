<?php

/**
 * Simple utility class for BQF functions
 */
class BQF {
	/**
	 * WhiteString is a utility function that will print a string with the all but the first lines indented.
	 */
	public static function WhiteString($lines) {
		$parts = explode("\n", $lines);
		return join("\n  ", explode("\n", $lines)) . "\n";
	}
}