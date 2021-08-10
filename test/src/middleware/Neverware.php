<?php
namespace net\peacefulcraft\apirouter\test\middleware;

use net\peacefulcraft\apirouter\spec\router\Middleware;
use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\router\Response;

class Neverware implements Middleware {

	public function run(array $config, Request $request, Response $response): bool {
		return false;	
	}
}

?>