<?php

/**
 * WPOLait does all the management of the JSONRpc for Wordpress plugins
 * that register for JSONRpc.
 */
class WPOLait {
	var $plugins;
	public function __construct() {
		$this->plugins = array();
	}
	public function register($phpClass, $jsName=null) {
		if (null==$jsName) {
			$jsName = $phpClass;
		}
		$this->plugins[$jsName] = $phpClass;
	}
	public function wp_enqueue_scripts() {
		wp_enqueue_script('es6-promise-polyfill', 
			plugins_url('/wpolait/bower_components/es6-promise-polyfill/promise.min.js',array()));
		wp_enqueue_script('wpolait',
			'/wpolait/', array('es6-promise-polyfill'));
	}
	public function init() {
		// Let all plugins register who want to provide JSON-Rpc services
		do_action('wpolait_register', $this);
	}
	/**
	 * GetURI returns the path for the JSONRpc API calls
	 * In Wordpress, it creates a specific path for the
	 * defined class.
	 * Outside Wordpress, it simply returns the path used to request the Javascript.
	 */
	public static function GetURI($path = '') {
		$uri = $_SERVER["REQUEST_URI"];
		if (function_exists('add_action')) {
			$queryStringLen = strlen($_SERVER["QUERY_STRING"]);
			if (0<$queryStringLen) {
				$uri = substr($uri, 0, -1*($queryStringLen+1));
			}
			return $uri . $path;
		} else {
			return $_SERVER["REQUEST_URI"];
		}
	}
	public function parse_request() {
		$root = "/wpolait/";
		$rootlen = strlen($root);
		$uri = self::GetURI();
		if (substr($uri, 0, strlen($root)) == $root) {
			$cls = substr($uri, $rootlen);
			if (""==$cls) {
				// We are serving our own JS
				header("Content-Type", "application/x-javascript");
				foreach ($this->plugins as $phpClass=>$jsClass) {
					$p = new Phpolait($phpClass);
					$p->renderTargetCode($jsClass);
				}
				die();
			}
			foreach ($this->plugins as $k=>$c) {
				if ($c == $cls) {
					error_log("Processing wpolait for class $cls");
					$p = new Phpolait($cls);
					$p->handle();
					die();
				}
			}
			// If we don't find the matching URL, we just exit - perhaps some other plugin or
			// wordpress part will manage this request.
			error_log("Failed to resolve wpolait class request $cls");
		}
	}
}

if (function_exists('add_action')) {
	$wpolait = new WPOLait();
	add_action('init', array($wpolait,'init'));
	add_action('parse_request', array($wpolait, 'parse_request'));
	add_action('wp_enqueue_scripts', array($wpolait, 'wp_enqueue_scripts'));
}