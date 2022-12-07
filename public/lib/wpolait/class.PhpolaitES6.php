<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * PhpolaitE6Promise
 * <summary>
 * Provides an interface for Phpolait using E6Promise library.
 * </summary>
 */

/**
 * Phpolait supports a plug-in Javascript library architecture. By default,
 * it uses the E6 Promise implementation and raw XMLHttpRequest
 */
class PhpolaitES6 extends PhpolaitJSLibrary {
	function jsAjax($jsServer, $set_args_from_data) {
		return <<<EOJS
		return new Promise(function(resolve,reject) {
			var req;
			if ('undefined'!==typeof window.XMLHttpRequest) {
				req = new XMLHttpRequest();
			} else if ('undefined'!==typeof window.ActiveXObject) {
				req = new ActiveXObject("Microsoft.XMLHttp")
			} else {
				reject("No supported HttpRequest implementation");
				return;
			}
			req.onreadystatechange = (function(resolve, reject, req) {
				return function() {
					if (4==req.readyState) {
						if (200==req.status) {
							if (('undefined'===typeof req.response) || (null==req.response)) {
								reject("Failed to parse response: " + req);
								return;
							}
							var res = req.response;
							if ('undefined'!==typeof res.error) {
								reject(res.error);
								return;
							}
							if ('undefined'!==typeof res.result) {
								resolve(res.result);
								return;
							}
							resolve(null);
							return;
						}
						reject("Failed with " + req.statusText);
					}
				};
			})(resolve, reject, req);
			req.timeout = 5000;
			req.open("POST", $jsServer, true);
			req.responseType = "json";
			req.send( JSON.stringify(jsonPost) );
		});
EOJS
	;	
	}
}
