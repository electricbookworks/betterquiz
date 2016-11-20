<?php 
if (!class_exists('SelfUrl')) {
/**
 * Utility class for constructing a url that refers 
 * to the current page.
 *
 */
class SelfUrl {
	protected $params;
	protected $fragment;
	public function __construct($params=null, $get=null) {
		$this->params = array();
		$this->addParams( null==$get ? $_GET : $get );
		if (null!=$params) {
			$this->addParams($params);
		}
	}
	public function add($key, $value) {
		$this->params[$key] = $value;
		return $this;
	}
	public function addParams($params) {
		foreach ($params as $key=>$value) {
			$this->params[$key] = $value;
		}		
	}
	public function fragment($fragment) {
		$this->fragment = $fragment;
	}
	public function url() {
		$path = $_SERVER['REQUEST_URI'];
		$qpos = strpos($path, "?");
		if (FALSE!==$qpos) {
			$path = substr($path, 0, $qpos);
		}
		return self::AbsoluteUrl($path, $this->params, $this->fragment);
	}
	public function html($text) {
		return '<a href="' . $this->url() . '">' . htmlentities($text) . '</a>';;
	}
	public function render($text) {
		echo $this->html($text);
	}

	public static function AbsoluteUrl($path='/', $params=array(), $fragment='') {
		$scheme = $_SERVER["REQUEST_SCHEME"];
		if (""==$scheme) {
			$scheme = "http";
		}
		$http = "http"==$scheme;
		if ($http) {
			$expectedPort = 80;
		} else {
			$expectedPort = 443;
		}
		$url = $scheme
		 	. "://" . $_SERVER["SERVER_NAME"];
		if ($expectedPort != $_SERVER["SERVER_PORT"]) {
			$url .= ":" . $_SERVER["SERVER_PORT"];
		}
		$url .= $path;
		if (0<count($params)) {
			$url .= '?' . http_build_query($params);
		}
		if (''!=$fragment) {
			$url .= '#' . $fragment;
		}
		return $url;
	}	
}

}	// !class_exists('SelfUrl')
