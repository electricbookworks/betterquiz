<?php

/**
 * A class that provides some functionality for building
 * sites.
 */
class Site {
	public function __construct() {
		$this->scripts = array();
		$this->styles = array();
		$this->imports = array();
		$this->js = array();
	}
	public function AddImports($imports) {
		foreach ($imports as $i) {
			$this->imports[] = $i;
		}
	}
	public function AddScripts($scripts) {
		foreach ($scripts as $s) {
			$this->scripts[] = $s;
		}
	}
	public function AddStyles($styles) {
		foreach ($styles as $s) {
			$this->styles[] = $s;
		}
	}
	public function AddJs($js) {
		foreach ($js as $j) {
			$this->js[] = $j;
		}
	}
	/**
	 * url returns a url mapped to the site's structure, or the original url if the url
	 * is already absolute.
	 */
	public static function url($url) {
		if (substr($url, 0, 2)=="//") {
			return $url;
		}
		if ((substr($url, 0, 5)=="http:") || (substr($url, 0, 6)=="https:")) return $url;
		// @TODO Insert the site prefix here, so that this can run on a shared server
		return $url;
	}

	public static function echoScripts($scripts) {
		foreach ($scripts as $s) {
			echo '<script type="text/javascript" src="' . self::url($s) . '"></script>' . "\n";
		}
	}
	public static function echoStylesheets($styles) {
		foreach ($styles as $s) {
			echo '<link rel="stylesheet" href="' . self::url($s) . '">' . "\n";
		}
	}
	public static function echoImports($imports) {
		foreach ($imports as $import) {
			echo '<link rel="import" href="' . self::url($import) . '">' . "\n";
		}
	}
	public function EchoScriptsAndStylesheets($scripts=array(), $styles=array(), $imports=array()) {
		self::echoScripts($this->scripts);
		self::echoScripts($scripts);
		self::echoStylesheets($this->styles);
		self::echoStylesheets($styles);
		self::echoImports($imports);
		self::echoImports($this->imports);
	}
	public function EchoJs() {
		if (0==count($this->js)) return;
		echo '<script type="text/javascript">jQuery(function() {';
		echo "\n";
		foreach ($this->js as $j) {
			echo $j;
		}
		echo "\n});</script>";
	}
}