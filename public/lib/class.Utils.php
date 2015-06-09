<?php

/**
 * Utils contains some general utilities for usage across the site.
 */
class Utils {
	/**
	 * Redirect redirects the user to the given URL with 
	 * the given parameters encoded in the Query parameter.
	 * The code dies after redirect, so calling Redirect() is 
	 * an exit from the executing script.
	 */
	public static function Redirect($url, $params=array()) {
		header("Location: " . self::Url($url, $params));
		die();
	}
	/**
	 * Param is a utility method to retrive a $_REQUEST
	 * parameter, without encountering 'array key does not exist'
	 * errors. The $default value is returned if the parameter
	 * does not exist.
	 */
	public static function Param($param, $default="") {
		if (array_key_exists($param, $_REQUEST)) {
			return $_REQUEST[$param];
		}
		return $default;
	}
	
	/**
	 * Returns a URL with each param url-encoded and
	 * added to the base URL. Note that the given url
	 * cannot contain parameters itself.
	 */
	public static function Url($url, $params=array()) {
		if (0==count($params)) {
			return $url;
		}
		$p = array();
		foreach ($params as $k=>$v) {
			$p[] = urlencode($k) . "=" . urlencode($v);
		}
		return $url . "?" . implode("&", $p);
	}

	/**
	 * IsEmail returns true if we think the given parameter is an
	 * email address, FALSE if we think it is a mobile number.
	 */
	public static function IsEmail($emailOrMobile) {
		return (FALSE!==strpos($emailOrMobile, "@"));
	}

	/**
	 * IsMobile returns true if we consider the given parameter
	 * a mobile number, false if we think it's an email address.
	 */
	public static function IsMobile($emailOrMobile) {
		return !(self::IsEmail($emailOrMobile));
	}


	/**
	 * FilterMobile returns an internationally formatted
	 * mobile number if the given parameter is a mobile number,
	 * or FALSE if the given parameter is not a mobile number.
	 * The returned number is in international format, without a leading
	 * +.
	 */
	public static function FilterMobile($mobile) {
		// First we remove all spaces
		$n = [];
		$mobile = trim($mobile);
		$len = strlen($mobile);
		for ($i=0; $i<$len; $i++) {
			$c = $mobile[$i];
			if (ctype_space($c)) continue;
			if ('+'==$c && 0==$i) continue;
			if ('0'==$c && 0==$i) {
				$n[] = "27";
				continue;
			}
			if (ctype_digit($c)) {
				$n[] = $c;
				continue;
			}
			// This is not whitespace, nor a leading + or 0, not a digit
			return FALSE;
		}
		return implode("", $n);
	}
}