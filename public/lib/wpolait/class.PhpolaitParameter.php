<?php

/**
 * Php wrapper around a parameter to method.
 */
class PhpolaitParameter {
	/** Phpolait library that generated this parameter object */
	protected $library;
	/** Reflection parameter object to the PHP parameter */
	protected $reflectParameter;
	function __construct($reflectParameter, $library) {
		$this->library = $library;
		$this->reflectParameter = $reflectParameter;
	}
	/**
	 * Return the name for the parameter in the target language.
	 */
	public function getTargetName() {
		return $this->reflectParameter->getName();
	}
	/**
	 * Return the ReflectionParameter object for this parameter.
	 */
	public function getReflect() { return $this->reflectParameter; }
	
	/**
	 * Return true if this parameter has a default value, false otherwise.
	 */
	public function isDefaultValueAvailable() { return $this->reflectParameter->isDefaultValueAvailable(); }
	/**
	 * Return the default value for this parameter.
	 */
	public function getDefaultValue() { return $this->reflectParameter->getDefaultValue(); }

}
