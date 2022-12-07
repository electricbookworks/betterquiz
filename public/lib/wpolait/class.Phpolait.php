<?php /* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * PhpOLait 2.0
 * <summary>
 * A total rewrite of php-o-lait for PHP 5.x and without the multiple dependencies
 * that existed in php-o-lait versions < 1.0. We have reduced the dependency
 * to JQuery out-of-the-box, but the developer can also use the Prototype.js 
 * javascript library, or extend with the addition of new Javascript 
 * library functionalities.
 *
 *
 * </summary>
 *
 * A simpler inclusion method will be used as well:
 * <script type="text/javascript" />
 *
 */


/**
 * Proxy class that converts a PHP class into a JSON-RPC server, both serving
 * the JSON-RPC requests and delivering
 * the Javascript proxy class to the browser when requested.
 */
class Phpolait {
	/** Javascript library to use to perform the Ajax / JSON-RPC request.
	 * By default, Phpolait will use the JQuery library. Phpolait also supports
	 * prototype.js, and it should be elementary to implement additional 
	 * library support.
	 */
	protected $library;
	
	/**
	 * @param $server The object that will provide the implementations to be served.
	 * @param $interface An interface that the $server implements. Only the methods of
	 * the defined interface will be exposed to the Javascript code. If this method is 
	 * blank, all public methods of $server will be exposed.
	 */
	public function __construct($server, $interface=null, $library=null) {
		global $_GET;
		if ($interface==null) {
			$interface = $server;
		}
		$this->server = $server;
		$this->interface = $interface;

		$reflectClass = new ReflectionClass($interface);
		$serverClass = new ReflectionClass($server);
		
		if (null==$library) {
			if ((!array_key_exists("library", $_GET)) || (null==$_GET["library"])) {
				$library = new PhpolaitES6();
			} else {
				$library = new $_GET["library"]();
			}
			$this->library = $library;
		} else {
			$this->library = $library;
		}
		
		$this->targetClass = $library->newClass($reflectClass, $serverClass->getName());
		
		foreach ($reflectClass->getMethods() as $method) {
			if ($method->isPublic()) {
				$targetMethod = $library->newMethod($method);
				$params = array();
				foreach ($method->getParameters() as $param) {
					$targetParameter = $this->library->newParameter($param);
					$targetMethod->addParameter($targetParameter);
				}
				$this->targetClass->addMethod($targetMethod);
			}
		}
	}

	public function renderTargetCode() {
		$this->library->renderTargetCode($this->targetClass);
	}

	public function handle() {
		/**
		 * The GET method is a call for the Javascript code.
		 * The POST method is a JSON Rpc call.
		 */
		if ($_SERVER["REQUEST_METHOD"]=="GET") {
			header("Content-Type: " . $this->library->getContentType());
			$this->library->renderTargetCode($this->targetClass);
		} else {
			header("Content-Type: application/json");
			/** @todo TODO: Work out how to handle exceptions. Surely try / catch is sufficient? */
			//set_error_handler("exception_error_handler");
			$return = array (
				"jsonrpc" => "2.0",
				"id" => null,
				"result" => null,
				"error" => null
			);
	
			$input = file_get_contents("php://input");
			
			/* If JSON decoding fails:
			 * this should never happen since our Javascript proxy should be doing
			 * the JSON encoding - i.e. our fault if the request isn't properly encoded.
			 */
			$request = json_decode($input);
			if ($request==null) {
				$return['error'] = array(
					"code" => -32700,
					"message" => "JSON parsing failed."
				);
			} else {			
				if (is_array($request)) {
					$response = array();
					foreach ($request as $r) {
						$thisResponse = $this->processRequest($r, $this->interface, $this->server);
						if (null!=$thisResponse) {
							array_push($response, $thisResponse);
						}
					}					
					// If all requests were notifications, we don't send any response
					if (0 == count($response)) $response = null;
				} else {
					error_log("About to call processRequest()");
					$response = $this->processRequest($request, $this->interface, $this->server);
				}
				if (null!=$response) {
					print(json_encode($response));
				}
			}
		}
	}
	
	/**
	 * Return a JSON RPC object resulting from processing the request.
	 */
	protected function processRequest($request, $interface, $server) {
		$return = array();
		
		/** @todo TODO Use STATICS / CONSTANTS for these errors */
		/*
		 * Possible JSON-RPC defined error codes are:
		 * -32700 : parse error
		 * -32600 : Invalid request
		 * -32601 : Method not found
		 * -32602 : Invalid params
		 * -32603 : Internal error
		 * -32099 .. -32000 : Reserved for implementation-defined server-errors.
		 */

		/* Identify the method and the parameters */
		$method = $request->method;

		if (!method_exists($interface, $method)) {
			$return['error'] = array (
				"code" => -32601,
				"message" => "No such method (" . $method . ") exists on this server."
			);
		} else {
			try {
				/** @todo TODO: When the method doesn't return anything, this appears to cause an error */
				// Sometimes, javascript JSON stringify will return an empty array as an empty object.
				$params = $request->params;
				if (is_object($request->params)) {
					$params = [];
					$i = 0;
					while (property_exists($request->params, $i)) {
						$params[] = $request->params->$i;
						$i++;
					}
				}
				error_log("About to call $method with params: " . implode(",", $params));
				$return['result'] = call_user_func_array(array(&$server, $method), $params);
			} catch (Exception $e) {
				$return['error'] = array (
					"code" => -1,	/** @todo TODO What error code should we set here? */
					"message" => $e->getMessage(),
					"data" => $e);
			}
		} 
		
		if (isset($request->id)) {
			$return["id"]=$request->id;
			return $return;
		} else {
			/* No id implies this is a notification, so don't return a response */
			return null;
		}
	}
}

// Deliberately not closing php tags so that no untoward whitespace creeps in.
