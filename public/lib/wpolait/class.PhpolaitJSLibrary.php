<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * <summary>
 * Base classes to be extended or modified for divergent Javascript libraries.
 * In general, to extend Phpolait to support a different library, it should
 * only be necessary to derive a new class from PhpolaitJSLibrary and provide the 
 * <i>internal</i> code for the actual ajax call.
 * </summary>
 */

/**
 * Base class for Phpolait Javascript translating classes.
 */
abstract class PhpolaitJSLibrary extends PhpolaitLibrary {

	public function getContentType() {
		return "application/x-javascript";
	}
	public function renderTargetCode($targetClass) {
		// error_log(__FILE__ . ":" . __LINE__ . " : targetClass = " . json_encode($targetClass) );
		print $this->getTargetCode($targetClass);
	}	
	public function getTargetCode($targetClass) {
		$js = $targetClass->getTargetCode();
        return $js;
		// if (array_key_exists("unpacked", $_GET)) {
		// 	return $js;
		// } else {
		// 	$packer = new JavaScriptPacker($js);
		// 	return $packer->pack();
		// }
	}
	
	/**
	 * Return a new PhpolaitClass for the given class name, with the associated
	 * ReflectionClass object.
	 */
	public function newClass($reflectClass, $className=null) {
		return new PhpolaitJSClass($reflectClass, $className, $this);
	}
	public function newMethod($reflectMethod) {
		return new PhpolaitJSMethod($reflectMethod, $this);
	}
	public function newParameter($reflectParameter) {
		return new PhpolaitJSParameter($reflectParameter, $this);
	}
	public abstract function jsAjax($server, $set_args_from_data);
}
