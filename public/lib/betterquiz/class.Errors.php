<?php

/**
 * Errors is a utility class for tracking whether 
 * errors have been added to some
 * processing route.
 */
class Errors {
	protected $_any;

	public function __construct() {
		$this->_any = false;
	}

	/** 
	 * True if any errors have occurred.
	 */
	public function Any() {
		return $this->_any;
	}

	/**
	 * Add an error with the given explanation
	 * to the list of errors. The errors are not stored
	 * in this class, but in the Flash class.
	 */
	public function Error($err) {
		Flash::New($err, "error");
		$this->_any = true;
	}
}