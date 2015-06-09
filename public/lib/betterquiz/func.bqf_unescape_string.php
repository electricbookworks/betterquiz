<?php

/**
 * bqf_unescape_string unescapes delimited strings. Any non-delimited strings are returned as-is.
 * A delimited string can contain a backslash escaped '\n', '\r', '\t', '\' and the 
 * strings own delimiter. Unescape returns the string with delimiting marks removed, and 
 * all escapes unescaped correctly.
 */
function bqf_unescape_string($in) {
	$i=0;
	$len = bqf_strlen($in);
	// Find the first non-whitespace character in the string
	while (($i<$len) && (bqf_isspace(bqf_substr($in, $i, 1)))) $i++;

	if ($i==$len) return $in;	// if the string is pure ws, we're done
	$c = bqf_substr($in, $i, 1);
	switch ($c) {
		case "\"":
			// fallthrough
		case "'":
			// fallthrough
		case "`":
			// continue after the switch
			break;
		default:
			// Any string that doesn't start with a string delimiter doesn't get escaping
			return $in;
	}
	// Only delimited strings get here
	$delimit = $c;
	$escaped = false;
	$out = array();
	$i=$i+1;
	$done = false;
	while (($i<$len) && (!$done)) {
		$c = bqf_substr($in, $i, 1);
		if ($escaped) {
			switch ($c) {
				case "n":
					array_push($out, "\n");
					break;
				case "t":
					array_push($out, "\t");
					break;
				case "r":
					array_push($out, "\r");
					break;
				case $delimit:
				case "\\":
					array_push($out,$c);
					break;
				default:
					// For any character except those listed above, we simply keep leading backslashes
					array_push($out, "\\");
					array_push($out, $c);
			}
			$escaped = false;
		} else {
			switch ($c) {
				case "\\":
					$escaped=true;
					break;
				case $delimit:
					$done = true;
					break;
				default:
					array_push($out, $c);					
			}
		}
		$i++;
	}
	// We don't bother to check for unterminated string- we'll just assume that
	// the Parser classes prevented that error. Of course we could be wrong, but hey, so what.
	// We also don't bother with anything in the content beyond the delimited string: we're
	// assuming the parser classes removed that.
	return join($out);
}