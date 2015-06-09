<?php

include_once("func.bqf_unescape_string.php");

function bqf_supports_utf8() {
	return false;
}

function bqf_strlen($s) {
	return strlen($s);
}

function bqf_substr($s, $i, $l=false) {
	if (FALSE===$l) {
		$l = bqf_strlen($l) - $i;
	}
	return substr($s, $i, $l);
}

function bqf_isspace($s) {
	return ctype_space($s);
}

function bqf_trim($s) {
	return trim($s);
}

function bqf_strpos($haystack, $needle) {
	return strpos($haystack, $needle);
}