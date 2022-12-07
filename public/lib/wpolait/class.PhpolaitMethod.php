<?php

/**
 * Php Wrapper around the reflected object for a PHP method.
 */
class PhpolaitMethod {
	/** Phpolait library that generated this method */
	protected $library;
	/** Array of JsParameters for the method */
	protected $params = array();
	/** ReflectionMethod class for the PHP method */
	protected $relectMethod;
	function __construct($reflectMethod, $library) {
		$this->reflectMethod = $reflectMethod;
		$this->library = $library;
	}
	/**
	 * Add a PhpolaitParameter parameter to the method.
	 * @param $param PhpolaitParameter object to add for the parameter.
	 */
	public function addParameter($param) {
		array_push($this->params, $param);
	}
	/**
	 * Return the Name of the method in the target environment.
	 * @return Name of the method in the target environment.
	 */
	public function getTargetName() {
		return $this->reflectMethod->getName();
	}	
}


