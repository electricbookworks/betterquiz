<?php
/**
 * Base class defining the interface for all Phpolait Translator libraries.
 */
abstract class PhpolaitLibrary {
	/** Return the content type that should be used to return the code to the browser.
	 */
	public function getContentType() { return "text/plain"; }
	/** Output the target code to the browser in appropriate fashion. */
	abstract public function renderTargetCode($targetClass);
	public function newClass($reflectClass, $className=null) {
		return new PhpolaitClass($reflectClass, $className, $this);
	}
	public function newMethod($reflectMethod) {
		return new PhpolaitMethod($reflectMethod, $this);
	}
	public function newParameter($reflectParameter) {
		return new PhpolaitParameter($reflectParameter, $this);
	}
}
