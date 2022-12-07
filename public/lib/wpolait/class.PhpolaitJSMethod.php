<?php

/**
 * Base class for Javascript methods wrapping Phpolait methods.
 */
class PhpolaitJSMethod extends PhpolaitMethod {
	public function __construct($reflectMethod, $library) {
		parent::__construct($reflectMethod, $library);
	}

	/**
	 * Return the code for the method in the target environment.
	 */
	public function getTargetCode() {
		$mname = $this->getTargetName();
		$pnames = array();
		$pdefaults = array();
		foreach ($this->params as $p) {
			array_push($pnames, $p->getTargetName());
			$jsDefault = $p->getJsDefault();
			if (strlen($jsDefault)>0)
				array_push($pdefaults, $jsDefault);
		}
		$pnames = join(",", $pnames);
		$pdefaults = join("\n", $pdefaults);
		/* PHP_SELF will be the URL of the including file: ie the file where the class with the
		 * server is defined.
		 */
		$js = <<< EOJS
"$mname" : function($pnames) {
    return this.\$call("$mname", arguments);
}
EOJS;
		return $js;
	}
}
