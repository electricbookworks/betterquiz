<?php

class PhpolaitJSClass extends PhpolaitClass {
	public function __construct($reflectClass, $className, $library) {
		parent::__construct($reflectClass, $className, $library);
	}
	
	/**
	 * Return the json2.js source code.
	 */
	public function getJson2() {
        return "";
    }

	
	/**
	 * Return the code defining the class in the target language.
	 * <p>
	 * All our javascript phpolait-internal functions 
	 * are named starting with $ signs. This ensures that
	 * we will never clash with PHP method names, since PHP
	 * won't allow a method name starting with $.
	 * </p>
	 */
	public function getTargetCode() {
		$cname = $this->getTargetName();	// Class name in js
		$mjs = array();						// Each method's Javascript
		foreach ($this->methods as $m) {
			$mname = $m->getTargetName();
			array_push($mjs, $m->getTargetCode());		
		}
		$mjs = join(",\n", $mjs);			// Javascript for all methods	

		$jsServer = json_encode(WPOLait::GetURI($this->className));	//$_SERVER["PHP_SELF"]);	// Location of the PHP JSON-RPC Server
		$set_args_from_data = <<<EOJS
				if (data instanceof Array) {
					var results = [];
					var errors = [];
					for (var i=0; i<data.length; i++) {
						results.push(data[i].result);
						errors.push(data[i].error);
					}
					args = [results, errors];
				} else {
					args = [data.result, data.error];
				}
EOJS;
		$jsAjax = $this->library->jsAjax($jsServer, $set_args_from_data);

		return $this->getJson2() . <<<EOJS
(function() {
var $cname = {
	$mjs,
	"\$batch" : function() {
		var jsonPost = arguments;
		$jsAjax
	},
	"\$callindex" : 0,
	"\$call" : function(method, argsArray) {
		var jsonPost = {
            "jsonrpc" : "2.0",
            "method" : method,
            "params" : argsArray,
            "id" : this.\$callindex++
        };
$jsAjax
	}
};
window.$cname = $cname;
})();
EOJS;
	}
}