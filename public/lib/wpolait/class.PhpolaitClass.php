<?php

/**
 * Phpwrapper around a PHP class.
 */
class PhpolaitClass {
	/** Phpolait translator library that generated this class */
	protected $library;
	/** Array of JsMethods for each method available on the object. */
	protected $methods = array();
	/** ReflectionClass object for the class */
	protected $reflectClass;
	/** Client-side class name for this object */
	protected $className;
	
	public function __construct($reflectClass, $className, $library) {
		$this->library = $library;
		$this->reflectClass = $reflectClass;
		if (null==$className) {
			$this->className = $reflectClass->getName();
		} else {
			$this->className = $className;
		}
	}
	/** 
	 * Add a method to the class.
	 * @param $method A PhpolaitMethod to be added.
	 */
	public function addMethod($method) {
		array_push($this->methods, $method);
	}
	/**
	 * Return the Name of the class in the destination environment.
	 */
	public function getTargetName() {
		return $this->className;
	}
	
	/**
	 * Return the target code for generating this class's proxy in
	 * the target language.
	 */
	public function getTargetCode() {
		return "-- RENDER TARGET CODE SHOULD BE SUBCLASSED --";
	}
}
