<?php

function encode_items(&$item, $key) {
	$item = utf8_encode($item);
}

class JsonRpcServer {
	private $errorMessages = array(
			'-32700' => 'Parse error',
			'-32600' => 'Invalid request',
			'-32601' => 'Method not found',
			'-32602' => 'Invalid parameters',
			'-32603' => 'Internal error',
			'-32604' => 'Authentication error'
	);
	
	private $errorCodes = array(
			'parseError' 			=> '-32700',
			'invalidRequest'		=> '-32600',
			'methodNotFound'		=> '-32601',
			'invalidParameters'		=> '-32602',
			'internalError'			=> '-32603',
			'authenticationError'	=> '-32604'
	);
	
	private $classes = array ();

	public function registerClass($obj) {
		$className = strtolower(get_class($obj));
		$this->classes[$className] = $obj;
	}

	public function handle() {
		try {
			if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
				$request = json_decode ( file_get_contents ( 'php://input' ), true );
				
				header ("Content-Type: application/json-rpc");
			} else {
				$request['method'] = $_GET['method'];
				$request['id'] = $_GET['id'];
				$request['params'] = json_decode($_GET['params'], true);
			}
			
			$errorCode = NULL;
			
			if (! empty ( $request ['method'] )) {
				$requestMethod = explode (".", strtolower($request['method']));
				$className = $requestMethod [0];
				$methodName = $requestMethod [1];
				
				$object = $this->classes [$className];
				if (is_object($object)) {
					$result = $object->{$methodName} ($request ['params']);
					if ($result !== FALSE) {
						if (is_array($result)) {
							array_walk_recursive($result, 'encode_items');
							
							$response = array (
								'jsonrpc' => '2.0',
								'id' => $request ['id'],
								'result' => $result);
						} else {
							$response = array (
								'jsonrpc' => '2.0',
								'id' => $request ['id'],
								'result' => array());
						}
					} else {
						$errorCode = 'invalidParameters';
					}
				} else {
					$errorCode = 'methodNotFound';
				}
			} else {
				$errorCode = 'methodNotFound';
			}
		} catch ( Exception $e ) {
			$errorCode = 'invalidRequest';
		}
		
		if ($errorCode != NULL) {
			$code = $this->errorCodes[$errorCode];
			$msg = $this->errorMessages[$code];
			
			$response = array (
					'jsonrpc' => '2.0',
					'id' => $request ['id'],
					'error' => array('code'=>(int) $code, 'message'=>$msg));
		}
		
		$response = json_encode ($response);
		echo $response;
	}
}
?>
