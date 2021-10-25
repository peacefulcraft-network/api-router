<?php
namespace net\peacefulcraft\apirouter\test\controllers\request;

use net\peacefulcraft\apirouter\router\Request;
use net\peacefulcraft\apirouter\spec\route\Controller;
use net\peacefulcraft\apirouter\router\Response;

class HTTPOptions implements Controller {
	public function handle(array $config, Request $request, Response $response): void {
		$response->setData($request->getUriParameters());
	}
}