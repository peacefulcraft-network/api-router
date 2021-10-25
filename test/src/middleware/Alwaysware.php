<?php
namespace net\peacefulcraft\apirouter\test\middleware;

use net\peacefulcraft\apirouter\spec\route\Middleware;
use net\peacefulcraft\apirouter\spec\route\IRequest;
use net\peacefulcraft\apirouter\spec\route\IResponse;

class Alwaysware implements Middleware {

	public function run(array $config, IRequest $Request): ?IResponse {
		return null;
	}
}

?>