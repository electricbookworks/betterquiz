<?php

class PhpolaitJSParameter extends PhpolaitParameter {
	function __construct($reflectParameter, $library) {
		parent::__construct($reflectParameter, $library);
	}
	
	/**
	 * Return the Javascript code that sets the default value for a parameter.
	 */
	public function getJsDefault() {	
		$pname = $this->getTargetName();
		if ($this->reflectParameter->isDefaultValueAvailable()) {
			return "\$argsArray.push ( ($pname===undefined) ? " .
				json_encode($this->reflectParameter->getDefaultValue()) . " : " .
				"$pname );";
		}
		return "\$argsArray.push( $pname );";
	}
}
