<?php
namespace net\peacefulcraft\apirouter\test\middleware;

use net\peacefulcraft\apirouter\router\Middleware;
use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\Response;

class Alwaysware implements Middleware {

	public function run(Request $request, Response $response): bool {
		return true;	
	}
}

?>